<?php

class PersonController extends Zend_Controller_Action {

    const VIEW_SCRIPT_NAME = 'addedit';
    const IS_REQUIRED_TEXT = ' is required';

    private $_dbTable = null;
    private $_logger = null;

    public function init() {
        $this->_dbTable = new Application_Model_DbTable_Person();
        $this->_logger = $this->getFrontController()
                        ->getParam('bootstrap')
                        ->getPluginResource('log')->getLog();
    }

    public function indexAction() {
        $this->view->persons = $this->_dbTable->fetchAll();
    }

    public function addAction() {
        $this->view->mode = ADD_MODE;
        $this->_helper->viewRenderer(self::VIEW_SCRIPT_NAME);
        if ($this->getRequest()->isGet()) {
            $this->_logger->log('addAction show mode', Zend_Log::ALERT);
            $this->view->formdata = $this->_dbTable->getEmptyRow();
        } else {
            $this->_onSubmit();
        }
    }

    public function add2Action() {
        $this->view->mode = ADD_MODE;
        $this->_helper->viewRenderer(self::VIEW_SCRIPT_NAME);
        if ($this->getRequest()->isGet()) {
            $this->_logger->log('addAction show mode', Zend_Log::ALERT);
            $this->view->formdata = $this->_dbTable->getEmptyRow();
        } else {
            $this->_onSubmit();
        }
    }

    public function editAction() {
        $this->view->mode = UPDATE_MODE;
        $this->_helper->viewRenderer(self::VIEW_SCRIPT_NAME);

        if ($this->getRequest()->isGet()) {
            $this->_logger->log('editAction show mode', Zend_Log::INFO);
            $id = (int) $this->getRequest()->getParam('id', 0);
            $row = $this->_dbTable->getWithDependencies($id);
            $this->view->formdata = $row;
        } else {
            $this->_onSubmit();
        }
    }

    public function townsAction() {
        if ($this->getRequest()->isGet()) {
            $term = $this->getRequest()->getParam('term');
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_Db::FETCH_OBJ);

            $sql = 'SELECT id, name FROM towns WHERE name like ?';
            $result = $db->fetchAll($sql, $term . '%');
            $this->_helper->json($result);
        }
    }

    private function _onSubmit() {
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if (!empty($formData['cancel'])) {
                $this->_helper->redirector('index');
            }
            $errors = $this->_validateInput($formData);

            if (empty($errors)) {
                $this->_dbTable->save($formData);
                $this->_helper->redirector('index');
            } else {
                $this->view->actionErrors = $errors;
                $this->view->formdata = $formData;
            }
        }
    }

    private function _validateInput(&$formData) {
        $validateNotEmpty = new Zend_Validate_NotEmpty();
        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                ->addFilter(new Zend_Filter_StripTags());

        $errors = array();

        $formData['name'] = $filterChain->filter($formData['name']);
        $formData['surname'] = $filterChain->filter($formData['surname']);

        if (!$validateNotEmpty->isValid($formData['name']))
            $errors[] = 'Name' . self::IS_REQUIRED_TEXT;

        if (!$validateNotEmpty->isValid($formData['surname']))
            $errors[] = 'Surname' . self::IS_REQUIRED_TEXT;

        $this->_validateDependencies($formData, $errors);

        return $errors;
    }

    private function _validateDependencies(&$formData, array &$errors) {
        $i = 1;
        $addresses = &$formData[Application_Model_DbTable_Person::ADDRESSES_KEY];

        foreach ($addresses as $addressArray) {
            $arrayAsString = '';
            foreach ($addressArray as $value)
                $arrayAsString .= $value;

            if (empty($arrayAsString)) {
                if (count($addresses) > 1)
                    unset($addresses[$i]);
                else
                    $errors[] = 'At least one address' . self::IS_REQUIRED_TEXT;
            }

            $i++;
        }
    }

}
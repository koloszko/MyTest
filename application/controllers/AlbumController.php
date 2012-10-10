<?php

class AlbumController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        echo $this->_helper->cosTam('rtrtrtr');
        $albums = new Application_Model_DbTable_Album();
        $this->view->albums = $albums->fetchAll();
    }

    public function addAction() {
        $form = new Application_Form_Album();
        $form->submit->setLabel('Add');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $albums = new Application_Model_DbTable_Album();
                $albums->insert($form->getValues());
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction() {
        $form = new Application_Form_Album();
        $form->submit->setLabel('Edit');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $albums = new Application_Model_DbTable_Album();
                $albums->update($form->getValues(), 'id = ' . $form->getValue('id'));
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = (int) $this->_getParam('id', 0);
            if ($id > 0) {
                $albums = new Application_Model_DbTable_Album();
                $form->populate($albums->get($id));
            }
        }
    }

    public function deleteAction() {
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $albums = new Application_Model_DbTable_Album();
                $albums->delete($id);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $albums = new Application_Model_DbTable_Album();
            $this->view->album = $albums->get($id);
        }
    }

}


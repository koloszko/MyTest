<?php

class AlbumController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        echo var_dump(Zend_Locale::getDefault());
        $locale = Zend_Registry::get('Zend_Locale');
        $symbols = Zend_Locale_Data::getList($locale, 'symbols');
        $var = Zend_Locale_Format::toNumber(12345.909);
        echo $var;
        echo str_replace($symbols['group'], '', $var);
        echo '<br/>';

        $cena = '0,90';
        //echo Zend_Locale_Format::getNumber($cena);
        $v = new Zend_Validate_Float();
        if (!$v->isValid($cena))
            echo var_dump($v->getErrors());

        $v1 = new Zend_Validate_GreaterThan(0);
        $f = new Zend_Filter_LocalizedToNormalized();
        if (!$v1->isValid($f->filter($cena)))
            echo var_dump($v1->getErrors());
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

    function pdfAction() {
        $invoice = Zend_Pdf::load(APPLICATION_PATH . "/../data/kwestionariuszosobowy.pdf");
        $page = $invoice->pages[0];

        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
        $page->setFont($font, 12);

        $page->drawText("Paweł Kołoszkooooo", 180, 721, 'WINDOWS-1250');
$font2 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
$page->setFont($font2, 36)
        ->drawText('Euro sign - €', 72, 720, 'UTF-8')
        ->drawText('Text with ąęł - à è ì', 72, 650, 'UTF-8');
        $invoice->save('C:\Tools\kwestionariuszosobowy.pdf');
    }

}


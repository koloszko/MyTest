<?php

class Application_Form_Auth extends Zend_Form {

    public function init() {
        $this->setName('auth');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username');
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Login');
        
        $this->addElements(array($username,$password,$submit));
    }

}


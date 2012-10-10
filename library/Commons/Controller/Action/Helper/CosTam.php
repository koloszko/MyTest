<?php

class Commons_Controller_Action_Helper_CosTam extends Zend_Controller_Action_Helper_Abstract {

    public function direct($message) {
        return $message . ' dodany tekst';
    }

}
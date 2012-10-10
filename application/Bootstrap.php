<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

//    protected function _initPlugins() {
//        $objFront = Zend_Controller_Front::getInstance();
//        $objFront->registerPlugin(new My_Controller_Plugin_ACL(), 1);
//        return $objFront;
//    }

    protected function _initConstants() {
        $options = $this->getOption('constants');

        if (is_array($options)) {
            foreach ($options as $key => $value) {
                if (!defined($key)) {
                    define($key, $value);
                }
            }
        }
    }
    protected function _initViewHelpers() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    protected function _initLocale() {
        $locale = new Zend_Locale('pl_PL');
        Zend_Registry::set('Zend_Locale', $locale);
    }
    
}


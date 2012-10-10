<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoggedUser
 *
 * @author pawel
 */
class Commons_View_Helper_LoggedInUser extends Zend_View_Helper_Abstract {

    public function loggedInUser() {
        $result = '';
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $userInfo = $auth->getStorage()->read();
            $result = 'Jesteś zalogowany jako ' . $userInfo->first_name . ' ' . $userInfo->last_name . 
                    '<a href="' . $this->view->url(array('controller' => 'auth', 'action' => 'logout'))
                    . '">Logout</a>';
        } else {
            $result = '<a href="' . $this->view->url(array('controller' => 'auth', 'action' => 'login'))
                    . '">Login</a>';
        }

        return $result;
    }

}

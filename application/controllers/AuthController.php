<?php
class AuthController extends Zend_Controller_Action {

    public function indexAction() {
        $this->_forward("login");
    }

    public function loginAction() {
        $flashMessenger = $this->_helper->FlashMessenger;
        $flashMessenger->setNamespace('actionErrors');
        $this->view->actionErrors = $flashMessenger->getMessages();
    }

    public function identifyAction() {
        $success = false;
        $message = "";
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $username = $formData['username'];
            $password = $formData['password'];

            if (empty($username) || empty($password)) {
                $message = 'Please provide a username and password.';
            } else {
                $db = Zend_Db_Table::getDefaultAdapter();
                $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users', 'username', 'password');

                $authAdapter->setIdentity($username);
                $authAdapter->setCredential($password);

                $auth = Zend_Auth::getInstance();
                if ($auth->authenticate($authAdapter)->isValid()) {
                    $auth->clearIdentity();
                    $auth->getStorage()->write($authAdapter->getResultRowObject(null, array('id', 'password')));
                    $success = true;
                    $this->_redirect('/');
                } else {
                    $auth->clearIdentity();
                    $message = 'Login failed';
                }
            }

            if (!$success) {
                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->setNamespace('actionErrors');
                $flashMessenger->addMessage($message);
                $this->_redirect('/auth/login');
            }
        }
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/');
    }

}
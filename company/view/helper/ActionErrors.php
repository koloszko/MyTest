<?php

/**
 * Description of ActionErrors
 *
 * @author pawel
 */
class Company_View_Helper_ActionErrors extends Zend_View_Helper_Abstract {

    public function actionErrors() {
        $result = '';
        $actionErrors = $this->view->actionErrors;
        
        if (isset($actionErrors) && !empty($actionErrors)) {
            $result .= '<ul>' . PHP_EOL;
            foreach ($actionErrors as $error) {
                $result .= '<li>' . $error . '</li>' . PHP_EOL;
            }
            $result .= '</ul>' . PHP_EOL;
        }
        
        return $result;
    }

}

?>

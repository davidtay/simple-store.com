<?php
/**
 *
 * @author Web Integration Technologies
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * LoggedIn helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class SimpleStore_View_Helper_IsLoggedIn
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;

    public function isLoggedIn () {
        $auth = Zend_Auth::getInstance();
        return $auth->hasIdentity();
    }
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view) {
        $this->view = $view;
    }
    
}

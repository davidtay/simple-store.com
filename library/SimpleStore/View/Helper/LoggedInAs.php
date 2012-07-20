<?php
/**
 *
 * @author Web Integration Technologies
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * LoggedInAs helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class SimpleStore_View_Helper_LoggedInAs
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    /**
     * 
     */
    public function loggedInAs () {
        $auth = Zend_Auth::getInstance();
		$checkout = new Zend_Session_Namespace("Checkout");
		$countText = "";
		if ($checkout->cart):
        	$count = $checkout->cart->count();
        	$countText = (($count) ? "(".$count . " item".(($count>1)?"s":"").")" : "");
        endif;
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
                
        if ($auth->hasIdentity()) {
        	$user = $auth->getIdentity();
            $name = $user->firstname ." ".$user->lastname;
            $roles = $user->getRoles();
            foreach($roles as $role):
            	if ($role->name=="admin")
                	$admin = true;
            endforeach;            
            
            $updateUrl = $this->view->url(array('module'=>'default', 'controller'=>'login',
                'action'=>'update'), null, true);
            $logoutUrl = $this->view->url(array('module'=>'default', 'controller'=>'login',
                'action'=>'logout'), null, true);
            $loggedInAs = "<span class='right'>Welcome $name | ".
            	((isset($admin) && $admin)?"<a href='/'>Store</a> | <a href='/admin'>Admin</a>" :"").
            	"</span><br />
            	<a href='$updateUrl'>My Account</a> |
            	<a href='$logoutUrl'>Logout</a> | 
            	<a href='/checkout/cart'>My Cart $countText</a> | 
            	<a href='/checkout/onepage'>Checkout</a>";
            
            return $loggedInAs;
        } 

        $registerUrl = $this->view->url(array('module'=>'default', 'controller'=>'login', 'action'=>'register'));
        $loginUrl = $this->view->url(array('module'=>'default', 'controller'=>'login', 'action'=>'index'));
        return "<a href='$registerUrl'>Register</a> | 
        	<a href='$loginUrl'>Login</a> | 
            <a href='/checkout/cart'>My Cart $countText</a> | 
        	<a href='/checkout/onepage'>Checkout</a>";
    }
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view) {
        $this->view = $view;
    }
    
}

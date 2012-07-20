<?php
require_once ('Zend/Controller/Plugin/Abstract.php');
/** 
 * @author David
 * 
 * 
 */
class SimpleStore_Controller_Plugin_TimeoutHandlerPlugin extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		//Zend_Session::rememberMe(1800);
	}
}
?>
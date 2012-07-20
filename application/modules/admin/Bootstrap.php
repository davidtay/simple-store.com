<?php
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

		
    protected function _initLayoutHelper ()
    {

        $this->bootstrap('frontController');
        $layout = Zend_Controller_Action_HelperBroker::addHelper(
        	new SimpleStore_Controller_Action_Helper_LayoutLoader());
    }	

} 
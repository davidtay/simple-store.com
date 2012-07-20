<?php
class SimpleStore_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract
{
    public function preDispatch ()
    {
        $bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
        $config = $bootstrap->getOptions();
        $module = $this->getRequest()->getModuleName();
        
        if ($module == "admin"):
		    $layout = Zend_Layout::getMvcInstance();
		    $layout->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts');
		    $layout->setLayout('admin');
        endif;
        
        if (isset($config[$module]['resources']['layout']['layout'])) {
            $layoutScript = $config[$module]['resources']['layout']['layout'];
            
            $this->getActionController()
                ->getHelper('layout')
                ->setLayout($layoutScript);
        }
        
        else {
            $layoutScript = $config['resources']['layout']['layout'];
            
            $this->getActionController()
                ->getHelper('layout')
                ->setLayout($layoutScript);        	
        }
        
    }
}

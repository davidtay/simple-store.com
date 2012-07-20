<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAcl ()
    {}
    protected function _initAppAutoload ()
    {
        $autoloader = new Zend_Application_Module_Autoloader(
        array('namespace' => 'App', 'basePath' => dirname(__FILE__)));
        return $autoloader;
    }
    protected function _initLayoutHelper ()
    {
        $this->bootstrap('frontController');
        $layout = Zend_Controller_Action_HelperBroker::addHelper(
        	new SimpleStore_Controller_Action_Helper_LayoutLoader());
    }
    protected function _initModuleLoaders ()
    {
        $this->bootstrap('Frontcontroller');
        $fc = $this->getResource('Frontcontroller');
        $modules = $fc->getControllerDirectory();
        foreach ($modules as $module => $dir) {
            $moduleName = strtolower($module);
            $moduleName = str_replace(array('-', '.'), ' ', $moduleName);
            $moduleName = ucwords($moduleName);
            $moduleName = str_replace(' ', '', $moduleName);
            $loader = new Zend_Application_Module_Autoloader(
            array('namespace' => $moduleName, 
            'basePath' => realpath($dir . "/../")));
        }
    }
    protected function _initViews ()
    {
        $this->bootstrap('layout');
        $view = $this->getResource('layout')->getView();
        $view->addHelperPath('SimpleStore/View/Helper', 
        'SimpleStore_View_Helper');
    }
    protected function _initViewHelpers ()
    {}
    protected function _initSessions ()
    {
        $this->bootstrap('session');
    }
}


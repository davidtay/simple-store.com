<?php

class Catalog_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$this->_helper->layout()->getView()->headTitle('Shop');
        $this->_helper->layout()->setLayout('index');

    }


}




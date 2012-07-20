<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
    	$this->_helper->layout()->getView()->headTitle('Simple Store');
        $this->_helper->layout()->setLayout('index');
//        $objResources = new SimpleStore_Acl_Resources();
//        $objResources->buildAllArrays();
//        $objResources->writeToDB();    	
    }

    public function aboutAction()
    {
        // action body
    }

    public function helpAction()
    {
        // action body
    }

    public function moviesAction()
    {
        // action body
    }

    public function musicAction()
    {
        // action body
    }

    public function tvAction()
    {
        // action body
    }

    public function contactAction()
    {
        // action body
    }


}
















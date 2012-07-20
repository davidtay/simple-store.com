<?php

class Admin_LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$this->_helper->layout()->getView()->headTitle("Login");
        $this->_helper->layout()->setLayout("admin");        
        
        $form = new Application_Form_Login();
        $form->setName("login");
        $form->setMethod("post");
        $form->setAction("/admin/login");
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
            	$values = $form->getValues();
		        $adapter = new SimpleStore_Auth_Adapter($values["email"], $values["password"]);
		        $result = Zend_Auth::getInstance()->authenticate($adapter);
                if (Zend_Auth::getInstance()->hasIdentity())
		            $this->_helper->redirector("index", "index","admin");
		        else 
		            $this->view->message = implode(" ", $result->getMessages()); 
            }
        }
        $this->view->form = $form; 
    }
    

}


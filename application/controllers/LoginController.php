<?php

class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$this->_forward("login");
    }

    public function loginAction()
    {
    	$this->_helper->layout()->getView()->headTitle("Login");
        $this->_helper->layout()->setLayout("index");        
        
        $form = new Application_Form_Login();
        $form->setName("login");
        $form->setMethod("post");
        $form->setAction("/login");
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $result = $this->_login($form->getValues());
		        if (Zend_Auth::getInstance()->hasIdentity())
		            $this->_helper->redirector("update", "login");
		        else 
		            $this->view->message = implode(" ", $result->getMessages()); 
            }
        }
        $this->view->form = $form; 
    }

    public function registerAction()
    {
    	if (!Zend_Auth::getInstance()->hasIdentity()){
	    	$this->_helper->layout()->getView()->headTitle("Register Account");
	        $this->_helper->layout()->setLayout("index");

	        $form = new Application_Form_Register();
	        $form->setName("register");
	        $form->setMethod("post");
	        $form->setAction("/login/register");
		        	        
	        $request = $this->getRequest();
	        if ($request->isPost()) {
	        	$form->populate($request->getPost());
	        	if ($form->isValid($request->getPost())) {
					$result = $this->_register($form);
					
			        if (Zend_Auth::getInstance()->hasIdentity())
			            $this->_helper->redirector("update", "login");
			        else 
			            $this->view->message = "No identity"; 					
	            }
	            else 
			        $this->view->message = "Form is not valid"; 					
	        }       		
	        $this->view->form = $form;
    	}
    	else
			$this->_helper->redirector("update","login");
    }

    public function updateAction()
    {
    	if (Zend_Auth::getInstance()->hasIdentity()){
	    	$this->_helper->layout()->getView()->headTitle("Update Account");
	        $this->_helper->layout()->setLayout("index"); 

	        $form = new Application_Form_Update();
	        $form->setName("update");
	        $form->setMethod("post");
	        $form->setAction("/login/update");
	        $data = Zend_Auth::getInstance()->getIdentity()->toArray();
	        $form->populate($data);
	        
	        $userId = (int)$data["id"];
	        $email = $form->getElement("email");
	        $validator = $email->getValidator("Zend_Validate_Db_NoRecordExists");
	        $validator->setExclude("id <> ".$userId);
	        $user = Doctrine::getTable("Default_Model_User")->find($userId);
    		$form->setRecord($user);
	        
	        $request = $this->getRequest();
	        if ($request->isPost()) {
	            if ($form->isValid($request->getPost())) {
					$result = $this->_update($form);
					
			        if (Zend_Auth::getInstance()->hasIdentity())
			            $this->_helper->redirector("update", "login");
			        else 
			            $this->view->message = "An error occurred"; 					
	            }
	        }   
	        $this->view->form = $form;	        
    	}
    	else
			$this->_helper->redirector("index", "login");    
    }

    public function forgotAction()
    {
    	if (!Zend_Auth::getInstance()->hasIdentity()){
	    	$this->_helper->layout()->getView()->headTitle("Forgot Password");
	        $this->_helper->layout()->setLayout("index"); 
	
	        $form = new Application_Form_Forgot();
	        $form->setName("forgot");
	        $form->setMethod("post");
	        $form->setAction("/login/forgot");
	        $request = $this->getRequest();
	        if ($request->isPost()) {
	        	$form->populate($request->getPost());
	        	if ($form->isValid($request->getPost())) {
					$result = $this->_forgot($form);
					
			        if ($result)
			            $this->view->message = "A password change form has been sent to your email"; 					
			        else 
			            $this->view->message = "Invalid email"; 					
	            }
	            else 
			        $this->view->message = "Form is not valid"; 					
	        }       		
		    $this->view->form = $form;	        
    	}
    	else
			$this->_helper->redirector("update","login");    
    }

    public function resetAction()
    {
    	if (!Zend_Auth::getInstance()->hasIdentity()){
	    	$hash = $this->getRequest()->getParam("reset_id");
	        $reset = Doctrine_Core::getTable("Default_Model_Reset")->findOneByHash($hash);
	        $expired = ($reset->expiration < time()) ? true : false;
    		
    		if ($hash && $reset && !$expired):
		    	$this->_helper->layout()->getView()->headTitle("Reset Password");
		        $this->_helper->layout()->setLayout("index"); 
		
		        $form = new Application_Form_Reset();
		        $form->setName("reset");
		        $form->setMethod("post");
		        $form->setAction("/login/reset/reset_id/$hash");

		        $userId = (int)$reset->user_id;
		        $user = Doctrine::getTable("Default_Model_User")->find($userId);
	    		$form->setRecord($user);
		        $email = $form->getElement("email");
		        $validator = new Zend_Validate_Identical($user->email);
		        $email->addValidator($validator);     
		        		        
		        $request = $this->getRequest();
		        if ($request->isPost()) {
		        	$form->populate($request->getPost());
		        	if ($form->isValid($request->getPost())) {
						$result = $this->_reset($form);
						
				        if (Zend_Auth::getInstance()->hasIdentity())
				            $this->_helper->redirector("update", "login");
				        else 
				            $this->view->message = "No identity"; 					
		            }
		            else 
				        $this->view->message = "Form is not valid"; 					
		        }       		
		        $this->view->form = $form; 
		    else :
				$this->_helper->redirector("index","login");        
    		endif;
    	}
    	else
			$this->_helper->redirector("update","login");          }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("/");
    }

    protected function _login($values) {
        $adapter = new SimpleStore_Auth_Adapter($values["email"], $values["password"]);
        $result = Zend_Auth::getInstance()->authenticate($adapter);
		return $result;
    }
    
    protected function _register($form){
    	if ($form->save())
    		return $this->_login($form->getValues());
    	else return false;
    }
    
    protected function _update($form){
    	return $form->save();
    }
    
    protected function _forgot($form){
    	$reset = $form->save();
    	if ($reset):
			// create view object
			$html = new Zend_View();
			$html->setScriptPath(APPLICATION_PATH . "/views/emails/");
						
			// assign values
			$user = $reset->User;
			$name = $user->firstname." ".$user->lastname;
			$html->assign("name", $name);
			$url = "http://".$_SERVER["HTTP_HOST"]."/login/reset/reset_id/".$reset->hash;
			$html->assign("url", $url);
			
			// create mail object
			$mail = new Zend_Mail("utf-8");
			
			// render view
			$bodyText = $html->render("reset.phtml");
			
			// configure base stuff
			$mail->addTo($form->getValue("email"));
			$mail->setSubject("Reset your Password");
			$mail->setFrom("support@mls-data.net","MLS Data");
			$mail->setBodyHtml($bodyText);
			return $mail->send();    	
    	endif;
    }
    
    protected function _reset($form){

    	if ($form->save()):
    		$values = $form->getValues();
    		return $this->_login($values);
    	else :
    		return false;
    	endif;
    }
    
}
















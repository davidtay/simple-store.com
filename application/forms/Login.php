<?php

class Application_Form_Login extends ZFDoctrine_Form_Model
{

    protected $_model = 'Default_Model_User';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array('firstname','lastname','salt','date_created','date_updated');

    // Make the password column's field type 'password' instead of the default 'text'
    protected $_fieldTypes = array(
        'password' => 'password'
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
        'email' => 'Email',
        'password' => 'Password',
    );

    protected function _preGenerate()
    {
    }

    protected function _postGenerate()
    {

    	
    	$email = $this->getElement('email');
        $email->addValidator('EmailAddress')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
		$email->autocomplete = 'off';
			
	    $password = $this->getElement('password');
	    $password->setLabel('Password:')
	            ->addValidator('StringLength', false, array(6,24))
	            ->setLabel('Password')
	            ->setRequired(true);
		$password->autocomplete = 'off';
		
    	$save = $this->getElement('Save');
    	$save->setLabel('Login');
    	$save->class = "submitButton";					
	            
    }
    
}


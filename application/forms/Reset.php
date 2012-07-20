<?php

class Application_Form_Reset extends Application_Form_Update
{
    // Let's ignore these fields
    protected $_ignoreFields = array('firstname','lastname','salt');

    protected function _postGenerate()
    {	
		$email = $this->getElement('email');
        $email->addValidator('EmailAddress')
			->addValidator('NotEmpty')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addFilter('StringToLower');
		$email->autocomplete = 'off';
		    	
	    $password = $this->getElement('password');
	    $password->setLabel('Password:')
	            ->addValidator('StringLength', false, array(6,24))
	            ->setLabel('Password')
	            ->setRequired(true);
		$password->autocomplete = 'off';
        $captcha = new Zend_Form_Element_Captcha('captcha',
	        array('label' => 'Write the characters in the image below into the field', 
		        'captcha' => array(
			        'captcha' => 'Image',
			        'wordLen' => 6,  
			        'timeout' => 300, 
			        'font' => $_SERVER['DOCUMENT_ROOT'] . '/captcha/LUCON.TTF',
			        'imgDir' => $_SERVER['DOCUMENT_ROOT'] . '/captcha/', 
			        'imgUrl' => '/captcha/'
	        	)
			)
		);
		
		$this->addElement($captcha);
		
		$this->removeElement('Save');
		$this->addElement('submit', 'Reset');
    } 
}


<?php

class Application_Form_Register extends ZFDoctrine_Form_Model
{
    protected $_model = 'Default_Model_User';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array('salt','date_created','date_updated');

    // Make the password column's field type 'password' instead of the default 'text'
    protected $_fieldTypes = array(
        'password' => 'password'
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
        'firstname' => 'First Name',
        'lastname' => 'Last Name',
        'email' => 'Email',
        'password' => 'Password',
    );

    protected function _preGenerate()
    {
        // this method is called before the form is generated
    }

    protected function _postGenerate()
    {	
    	$firstname = $this->getElement('firstname');
        $firstname->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
		$firstname->autocomplete = 'off';

    	$lastname = $this->getElement('lastname');
        $lastname->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
		$lastname->autocomplete = 'off';		

		$email = $this->getElement('email');
        $email->addValidator('EmailAddress')
        	->addValidator(new Zend_Validate_Db_NoRecordExists(
        		array(
        			'table' => Doctrine_Core::getTable('Default_Model_User')->getTableName(),
        			'field' => 'email'
        			)
        		)
        	)
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
		
    	$save = $this->getElement('Save');
    	$save->setLabel('Register');
    	$save->class = "submitButton";				
    }
    
    /**
     * Save the form data
     * @param bool $persist Save to DB or not
     * @return Doctrine_Record
     */
    public function save($persist = true) {
        $inst = $this->getRecord();

        $values = $this->getValues();
        
        $password = $values['password'];
        $salt = sha1(time());
        $values['salt'] = $salt;
        $values['password'] = sha1($password.$salt);

        if ("Application_Form_Register" == get_class($this))
        	$values['date_created'] = date("Y-m-d H:i:s");
        else
        	$values['date_updated'] = date("Y-m-d H:i:s");
        
        $this->_adapter->setRecordValues($values);

        if($persist) {
            $this->_adapter->saveRecord();
        }

        foreach($this->getSubForms() as $subForm) {
            if ($subForm instanceof ZFDoctrine_Form_ModelSubForm) {
                $subForm->save($persist);
            }
        }

        $this->_postSave($persist);
        return $inst;
    }

}


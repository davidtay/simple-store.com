<?php

class Application_Form_Forgot extends ZFDoctrine_Form_Model
{
    protected $_model = 'Default_Model_Reset';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array('hash','user_id','expiration');

    // Make the password column's field type 'password' instead of the default 'text'
    protected $_fieldTypes = array(
        //'' => ''
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
        'email' => 'Email',
    );

    protected function _preGenerate()
    {
    }

    protected function _postGenerate()
    {
		$this->removeElement('Save');
		$this->addElement('submit', 'request', array('label'=>"Request Password"));
		$request = $this->getElement("request");
    	$request->class = "submitButton";					
    }
    
    public function init(){
    	
      	$email = $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower','StripTags'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
                array('NotEmpty'),
            ),
            'required'   => true,
            'label'      => 'Email:',
            'autocomplete' => 'off',
        ));
    }

    /**
     * Save the form data
     * @param bool $persist Save to DB or not
     * @return Doctrine_Record
     */
    public function save($persist = true) {
        $inst = $this->getRecord();

        $values = $this->getValues();
        
		$user = Doctrine_Core::getTable('Default_Model_User')->findOneByEmail($values['email']);
		if (!$user)
			return false;
        $values['user_id'] = $user->id;
        $values['hash'] = sha1(time());
        $values['expiration'] = time() + 1800;
        $values['reset'] = 0;
        
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


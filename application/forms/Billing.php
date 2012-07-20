<?php

class Application_Form_Billing extends ZFDoctrine_Form_Model
{
    protected $_model = 'Default_Model_Address';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array('shipping','user_id','form');

    // Change default type 'text'
    protected $_fieldTypes = array(
    	'form'=>'hidden'
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
        'name' => 'Billing Name',
        'address' => 'Address',
    	'city' => 'City',
        'state' => 'State',
        'zip' => 'Zip',
        'phone' => 'Phone',
    );
    

    protected function _preGenerate()
    {
    	$form = $this->addElement('hidden','form',array('value'=>'billing'));
    }

    protected function _postGenerate()
    {	
    	$form = $this->getElement('form');
    	$form->removeDecorator('HtmlTag');
    	$form->removeDecorator('Label');
    	
    	$address = $this->getElement('address');
        $address->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

    	$city = $this->getElement('city');
        $city->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');	
			
    	$state = $this->getElement('state');
        $state->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
			
    	$zip = $this->getElement('zip');
        $zip->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');			

    	$phone = $this->getElement('phone');
        $phone->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
			
    	$save = $this->getElement('Save');
    	$save->setLabel('Continue');
    	$save->onclick = "return saveOnePage('billing')";
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
        $values['billing'] = 1;
        $values['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;
        
        $inst = $this->_adapter->getRecord();
        if (!$inst->id)
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


<?php

class Application_Form_Payment extends ZFDoctrine_Form_Model
{
    protected $_model = 'Checkout_Model_Order';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array(
    	'billing_name','billing_address','billing_city','billing_state','billing_zip','billing_phone',
    	'shipping_name','shipping_address','shipping_city','shipping_state','shipping_zip','shipping_phone',
    
    	'method','reviewed','cart','sub_total','shipping','tax','grand_total','user_id','session_id','increment_id');

    // Change default type 'text'
    protected $_fieldTypes = array(
    	'form'=>'hidden',
    	'card_type'=>'select',
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
        'card_type' => 'Credit Card Type',
    	'card_number' => 'Credit Card Number',
        'card_exp' => 'Expiration Date',
        'card_csc' => 'Card Verification Number',
    );
    

    protected function _preGenerate()
    {
    	$form = $this->addElement('hidden','form',array('value'=>'payment'));
    }

    protected function _postGenerate()
    {	
    	$form = $this->getElement('form');
    	$form->removeDecorator('HtmlTag');
    	$form->removeDecorator('Label');
    	
    	$cardType = $this->getElement('card_type');
        $cardType->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
		$cards = Checkout_Model_Card::Cards();
		foreach ($cards as $cardValue=>$cardAr):
			$options[$cardValue] = $cardAr['label'];
		endforeach;
		$cardType->addMultiOptions($options);
		
    	$cardNumber = $this->getElement('card_number');
        $cardNumber->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');  

    	$cardExp = $this->getElement('card_exp');
        $cardExp->setRequired(true)
        	->setAttrib('readonly', 'true')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty')
			->addValidator(new Zend_Validate_Date()); 
			
    	$cardCsc = $this->getElement('card_csc');
        $cardCsc->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
			
    	$save = $this->getElement('Save');
    	$save->setLabel('Continue');
    	$save->onclick = "return saveOnePage('payment')";
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
        $values['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;
        $values['session_id'] = Zend_Session::getId();
        
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


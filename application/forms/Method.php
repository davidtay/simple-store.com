<?php

class Application_Form_Method extends ZFDoctrine_Form_Model
{
    protected $_model = 'Checkout_Model_Order';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array(
    	'card_name','card_type','card_number','card_exp','card_csc',
    	'reviewed','cart',
    	'sub_total','shipping','tax','grand_total',
    	'user_id','session_id','increment_id');

    // Change default type 'text'
    protected $_fieldTypes = array(
    	'form'=>'hidden',
    	'method'=>'radio',
    	'billing_name'=>'hidden',
    	'billing_address'=>'hidden',
    	'billing_city'=>'hidden',
    	'billing_state'=>'hidden',
    	'billing_zip'=>'hidden',
    	'billing_phone'=>'hidden',
    	'shipping_name'=>'hidden',
    	'shipping_address'=>'hidden',
    	'shipping_city'=>'hidden',
    	'shipping_state'=>'hidden',
    	'shipping_zip'=>'hidden',
    	'shipping_phone'=>'hidden',
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
        'method' => 'Select Shipping Method',
    );
    

    protected function _preGenerate()
    {
    	$form = $this->addElement('hidden','form',array('value'=>'method'));
    }

    protected function _postGenerate()
    {	
    	$elements = $this->getElements();
    	foreach ($elements as $element):
    		if ($element->getType()=="Zend_Form_Element_Hidden"):
		    	$element->removeDecorator('HtmlTag');
		    	$element->removeDecorator('Label');
    		endif;
    	endforeach;
    	
//    	$form = $this->getElement('form');
//    	$form->removeDecorator('HtmlTag');
//    	$form->removeDecorator('Label');
    	
    	$method = $this->getElement('method');
        $method->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
		$methods = Checkout_Model_Method::Methods();
		foreach ($methods as $methodValue=>$methodAr):
			$options[$methodValue] = $methodAr['label']." - ".$methodAr['rate'];
		endforeach;
		
		$method->addMultiOptions($options);
			
    	$save = $this->getElement('Save');
    	$save->setLabel('Continue');
    	$save->onclick = "return saveOnePage('method')";
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


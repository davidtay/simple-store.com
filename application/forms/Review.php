<?php

class Application_Form_Review extends ZFDoctrine_Form_Model
{
    protected $_model = 'Checkout_Model_Order';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array(
    	'billing_name','billing_address','billing_city','billing_state','billing_zip','billing_phone',
    	'shipping_name','shipping_address','shipping_city','shipping_state','shipping_zip','shipping_phone',
    	'method',
    	'card_type','card_number','card_exp','card_csc',
    	'user_id','session_id');
    // Change default type 'text'
    protected $_fieldTypes = array(
    	'sub_total'=>'hidden',
    	'shipping'=>'hidden',
    	'tax'=>'hidden',
    	'grand_total'=>'hidden',
    	'status' =>'hidden',
    	'increment_id'=>'hidden',
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(

    );
    

    protected function _preGenerate()
    {
    	$form = $this->addElement('hidden','form',array('value'=>'review'));
    	$cart = $this->addElement('hidden','cart');
    	$status = $this->addElement('hidden','status');
    	$increment_id = $this->addElement('hidden','increment_id');
    }

    protected function _postGenerate()
    {	
    	$form = $this->getElement('form');
    	$form->removeDecorator('HtmlTag');
    	$form->removeDecorator('Label');
    	
    	$cart = $this->getElement('cart');
    	$cart->removeDecorator('HtmlTag');
    	$cart->removeDecorator('Label'); 
    	
    	$status = $this->getElement('status');
    	$status->removeDecorator('HtmlTag');
    	$status->removeDecorator('Label');     	

    	$incrementId = $this->getElement('increment_id');
    	$incrementId->removeDecorator('HtmlTag');
    	$incrementId->removeDecorator('Label');  

    	$subTotal = $this->getElement('sub_total');
    	$subTotal->removeDecorator('HtmlTag');
    	$subTotal->removeDecorator('Label');        	

    	$shipping = $this->getElement('shipping');
    	$shipping->removeDecorator('HtmlTag');
    	$shipping->removeDecorator('Label');      

    	$tax = $this->getElement('tax');
    	$tax->removeDecorator('HtmlTag');
    	$tax->removeDecorator('Label');  
    	
    	$grandTotal = $this->getElement('grand_total');
    	$grandTotal->removeDecorator('HtmlTag');
    	$grandTotal->removeDecorator('Label');      	
    	    	
    	$save = $this->getElement('Save');
    	$save->setLabel('Place Order');
    	$save->class = "placeOrderButton";			
    }
    
    /**
     * Save the form data
     * @param bool $persist Save to DB or not
     * @return Doctrine_Record
     */
    public function save($persist = true) {
        $inst = $this->getRecord();

        $values = $this->getValues();
        $values['reviewed'] = 1;
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


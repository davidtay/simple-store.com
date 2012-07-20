<?php

class Admin_Form_Order extends ZFDoctrine_Form_Model
{
    protected $_model = 'Checkout_Model_Order';

    // By default, many-relations will be generated, but you can disable them.
    protected $_generateManyFields = false;

    // Let's ignore these fields
    protected $_ignoreFields = array(
    	"method",
    	"billing_name",
    	"billing_address",
    	"billing_city",
        "billing_state",
    	"billing_zip",
        "billing_phone",
    	"shipping_name",
    	"shipping_address",
       	"shipping_city",
    	"shipping_state",
        "shipping_zip",
        "shipping_phone",
		"card_type",
		"card_number",
    	"card_exp",
    	"card_csc",
    	"increment_id",    
    	"sub_total",
    	"shipping",
    	"tax",
    	"grand_total",
    	'user_id','session_id','date_created','date_updated');
    // Change default type 'text'
    protected $_fieldTypes = array(
		"status"=>"hidden",
    );

    // Give some human-friendly labels for the fields:
    protected $_fieldLabels = array(
		
    );
    

    protected function _preGenerate()
    {

    }

    protected function _postGenerate()
    {	    	
    	$save = $this->getElement('Save');
    	$save->setLabel('Cancel Order');
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


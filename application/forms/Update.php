<?php

class Application_Form_Update extends Application_Form_Register
{
    protected function _postGenerate()
    {	
    	parent::_postGenerate();
    	$save = $this->getElement('Save');
    	$save->setLabel('Update');
    }	
}


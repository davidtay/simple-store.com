<?php

class Checkout_CartController extends Zend_Controller_Action
{
	protected $_cart;
	
    public function init()
    {
    }

    public function indexAction()
    {
    	$this->_helper->layout()->getView()->headScript()->appendFile('/js/checkout/cart.js');
    	$checkout = new Zend_Session_Namespace("Checkout");
        
        if (is_object($checkout->cart)):
        	$this->view->items = $checkout->cart->getItems();
        	$this->view->subTotal = $checkout->cart->getSubTotal();
        	$this->view->shipping = $checkout->cart->getShipping();
        	$this->view->tax = $checkout->cart->getTax();
        	$this->view->grandTotal = $checkout->cart->getGrandTotal();
        endif;
    }

    public function addAction()
    {
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
    	
        $id = (int)$this->getRequest()->getParam("id");
        $qty = (int)$this->getRequest()->getParam("qty");
         
        if ($qty && $id):
			$productTable = Doctrine_Core::getTable('Catalog_Model_Product');
        	$product = $productTable->find($id);   
        	if ($product->id) :
        	
				$checkout = new Zend_Session_Namespace("Checkout");
				$this->_cart = $checkout->cart;
				
				if (!$this->_cart):
					$this->_cart = new Checkout_Model_Cart();
					$checkout->cart = $this->_cart;
				endif;
        	
        		echo $this->_cart->addToItems($qty, $id, $product);
				        		
        	else:
				$this->_helper->redirector->gotoUrl("/catalog");
        	endif;
        else :
        	$this->_helper->redirector('index');
        endif;     
    }
    
    public function updateAction(){

    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

        $qtys = $this->getRequest()->getParam("qtys");
        
        if (is_array($qtys) && count($qtys)>0):
        
			$checkout = new Zend_Session_Namespace("Checkout");
			$this->_cart = $checkout->cart;
			
        	foreach($qtys as $id=>$qty):
        		$this->_cart->updateItem($qty, $id);        		
        	endforeach;
        	
        	echo 1;
        else :
        	echo 0;
        endif;
		
    }


}






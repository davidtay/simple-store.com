<?php

class Catalog_ProductController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    	$this->_helper->layout()->getView()->headTitle('View Product');
    	$this->_helper->layout()->getView()->headScript()->appendFile('/js/catalog/product.js');
    	$this->_helper->layout()->setLayout('index');
        
         $id = (int)$this->getRequest()->getParam("id");
        
        if ($id):
			$productTable = Doctrine_Core::getTable('Catalog_Model_Product');
        	$product = $productTable->find($id);   
        	if ($product->id) :
        		$this->view->product = $product;
				$checkout = new Zend_Session_Namespace("Checkout");
				$cart = $checkout->cart;
				if ($cart && $cart->count($id)) :   		
        			$this->view->qty = $cart->count($id);
        		else :
        			$this->view->qty = 1;
        		endif;
        	else:
				$this->_helper->redirector->gotoUrl("/catalog");
        	endif;
        else :
        	$this->_helper->redirector('index');
        endif;       
        
    }


}




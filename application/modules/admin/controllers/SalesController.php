<?php

class Admin_SalesController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
		$sales = new Sales_Model_Sales();
		$orders = $sales->getOrders();
         
        $this->view->orders = $orders;
    }

    public function viewAction()
    {
    	$id = (int)$this->getRequest()->getParam('id');
    	
    	if ($id):
			$sales = new Sales_Model_Sales();
			$order = $sales->getOrder($id);
			switch ($order->status):
    			case Checkout_Model_Order::CANCELLED :
    				$this->view->canCancel = false;
    				$this->view->canInvoice = false;
    				$this->view->canShip = false;
    				break;
    			case Checkout_Model_Order::PENDING :
    				$this->view->canCancel = true;
    				$this->view->canInvoice = true;
    				$this->view->canShip = false;
    			case Checkout_Model_Order::PROCESSING :
    				$this->view->canCancel = true;
    				$this->view->canInvoice = true;
    				$this->view->canShip = false;
    				break;
    			case Checkout_Model_Order::COMPLETED :
    				$this->view->canCancel = false;
    				$this->view->canInvoice = false;
    				$this->view->canShip = true;
    				break;
       		endswitch;
	        $this->view->order = $order;    
	        $this->view->items = unserialize($order->cart);	
			
	    else:
		    $this->_helper->redirector("index", "sales", "admin");
    	endif;
    }

    public function deleteAction()
    {
        // action body
    }

    public function cancelAction()
    {
    	$id = (int)$this->getRequest()->getParam('id');
    	
    	if ($id):
			$sales = new Sales_Model_Sales();
			$order = $sales->getOrder($id);
			if ($order->status == Checkout_Model_Order::PENDING || $order->status == Checkout_Model_Order::PROCESSING):
				$order->status = Checkout_Model_Order::CANCELLED;
				$order->save();
			endif;
    	endif;
		$this->_helper->redirector("index", "sales", "admin");
    }

    public function invoiceAction()
    {
    	$id = (int)$this->getRequest()->getParam('id');
    	
    	if ($id):
			$sales = new Sales_Model_Sales();
			$order = $sales->getOrder($id);
			if ($this->getRequest()->isPost() && $this->getRequest()->getParam("invoice")):
				$order->status = Checkout_Model_Order::COMPLETED;
				$order->save();
		    	$this->_helper->redirector("index", "sales", "admin");
			else:
				if ($order->status == Checkout_Model_Order::PENDING):
					$order->status = Checkout_Model_Order::PROCESSING;
					$order->save();
				endif;			
			endif;

			$this->view->order = $order;    
	        $this->view->items = unserialize($order->cart);	
			
	    else:
		    $this->_helper->redirector("index", "sales", "admin");
    	endif;
    }

    public function shipAction()
    {
     	$id = (int)$this->getRequest()->getParam('id');
    	
    	if ($id):
			$sales = new Sales_Model_Sales();
			$order = $sales->getOrder($id);
			if ($this->getRequest()->isPost() && $this->getRequest()->getParam("ship")):
				$order->status = Checkout_Model_Order::SHIPPED;
				$order->save();
		    	$this->_helper->redirector("index", "sales", "admin");
			endif;

			$this->view->order = $order;    
	        $this->view->items = unserialize($order->cart);	
			
	    else:
		    $this->_helper->redirector("index", "sales", "admin");
    	endif;
    }


}












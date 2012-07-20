<?php

class Checkout_OnepageController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    	$checkout = new Zend_Session_Namespace("Checkout");
    	if ($checkout->cart):
        	$items = $checkout->cart->getItems();
        endif;      	
    	$auth = Zend_Auth::getInstance();

        if (!is_array($items) || count($items)==0):
        	$this->_redirect("/checkout/cart");
    	elseif (!$auth->hasIdentity()):
        	$this->_redirect("/login");
    	else :

	        $this->view->billingForm = $this->_billingForm();
    		$this->view->shippingForm = $this->_shippingForm();
    		$this->view->methodForm = $this->_methodForm();
    		$this->view->paymentForm = $this->_paymentForm();
    		$this->view->reviewForm = $this->_reviewForm();
    		
    		$this->view->items = $items;
    		
    		$this->view->subTotal = $checkout->cart->getSubTotal();
        	$this->view->shipping = $checkout->cart->getShipping();
        	$this->view->tax = $checkout->cart->getTax();
        	$this->view->grandTotal = $checkout->cart->getGrandTotal();
        	        	
    		$this->_helper->layout()->getView()->headScript()->appendFile('/js/checkout/onepage.js');
    		$this->_helper->layout()->getView()->headScript()->appendFile('/js/jquery-ui/js/jquery-ui-1.8.18.custom.min.js');
    		$this->_helper->layout()->getView()->headLink()->appendStylesheet('/js/jquery-ui/css/overcast/jquery-ui-1.8.18.custom.css');
    		
    	endif;
    }
    
    public function saveAction()
    {
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$auth = Zend_Auth::getInstance();
    	$checkout = new Zend_Session_Namespace("Checkout");
		$request = $this->getRequest();
    	$response = array("success"=>null);
        if ($request->isPost()) :
            $form = $request->getParam('form');
            $post = $request->getPost();
            switch ($form) :
                case 'billing':
                	$billingForm = $this->_billingForm();
                    $billingForm->populate($post);
                    if ($billingForm->isValid($post)):
                    	$billingForm->save();
                    	$response['success'] = 1;
	                else :
	                	$response['success'] = 0;
	                	$response['errors'] = $billingForm->getErrors();
	                endif;
                    break;
                case 'shipping':
                	$shippingForm = $this->_shippingForm();
                    $shippingForm->populate($post);
                    if ($shippingForm->isValid($post)):
                    	$shippingForm->save();
                    	$response['success'] = 1;
	                else :
	                	$response['success'] = 0;
	                	$response['errors'] = $shippingForm->getErrors();
	                endif;
                    break;
                case 'method':
                	$methodForm = $this->_methodForm();
                    $methodForm->populate($post);
                    if ($methodForm->isValid($post)):
                    	$methodForm->save();
			    		$methods = Checkout_Model_Method::Methods();
			    		$order = $methodForm->getRecord();
			    		$method = $order->method;
			    		$shipping = (in_array($method,array_keys($methods))) ? $methods[$method]['rate'] : $methods['ground']['rate'];                    	
                    	$checkout->cart->setShipping($shipping);
				        $q = Doctrine_Query::create()->select('*')
				            ->from('Default_Model_Address a')
				            ->where("a.user_id=?", $auth->getIdentity()->id);
				        $addresses = $q->execute();
				        $billing = $addresses->get(0);
				        $shipping = $addresses->get(1);
				        $order->billing_name = $billing->name;
				        $order->billing_address = $billing->address;
				        $order->billing_city = $billing->city;
				        $order->billing_state = $billing->state;
				        $order->billing_zip = $billing->zip;
				        $order->billing_phone = $billing->phone;
				        $order->shipping_name = $shipping->name;
				        $order->shipping_address = $shipping->address;
				        $order->shipping_city = $shipping->city;
				        $order->shipping_state = $shipping->state;
				        $order->shipping_zip = $shipping->zip;
				        $order->shipping_phone = $shipping->phone;
				        $order->save();
                    	$response['success'] = 1;
	                else :
	                	$response['success'] = 0;
	                	$response['errors'] = $methodForm->getErrors();
	                endif;
                    break;
                case 'payment':
                	$paymentForm = $this->_paymentForm();
                    $paymentForm->populate($post);
                    if ($paymentForm->isValid($post)):
                    	$paymentForm->save();
                    	$response['success'] = 1;
	                else :
	                	$response['success'] = 0;
	                	$response['errors'] = $paymentForm->getErrors();
	                endif;
                    break;
            endswitch;
    	endif;
    	
	   	$response = json_encode($response);
		$this->getResponse()
		    ->setHeader('Content-Type', 'application/json')
		    ->appendBody($response);
    }
    
    public function totalsAction()
    {
        $this->_helper->layout()->setLayout("ajax");
    	$checkout = new Zend_Session_Namespace("Checkout");
    	$this->view->subTotal = $checkout->cart->getSubTotal();
        $this->view->shipping = $checkout->cart->getShipping();
        $this->view->tax = $checkout->cart->getTax();
        $this->view->grandTotal = $checkout->cart->getGrandTotal();
    }    

    protected function _billingForm()
    {
    	$auth = Zend_Auth::getInstance();
    	$billingForm = new Application_Form_Billing();
        $billingForm->setName("billing");
        $billingForm->setMethod("post");
        $billingForm->setAction("/checkout/onepage/save");
        $billingAddress = Doctrine_Query::create()->select('*')
            ->from('Default_Model_Address a')
            ->where("a.user_id=?", $auth->getIdentity()->id)
            ->andWhere("billing = ?", 1)
            ->fetchOne();
            
        if ($billingAddress && $billingAddress->id)
            $billingForm->setRecord($billingAddress);  
            
        return $billingForm;	
    }

    protected function _shippingForm()
    {
    	$auth = Zend_Auth::getInstance();
    	$shippingForm = new Application_Form_Shipping();
        $shippingForm->setName("shipping");
        $shippingForm->setMethod("post");
        $shippingForm->setAction("/checkout/onepage/save");
        $shippingAddress = Doctrine_Query::create()->select('*')
            ->from('Default_Model_Address a')
            ->where("a.user_id=?", $auth->getIdentity()->id)
            ->andWhere("shipping = ?", 1)
            ->fetchOne();
            
        if ($shippingAddress && $shippingAddress->id)
            $shippingForm->setRecord($shippingAddress);	

        return $shippingForm;
    }

    protected function _methodForm()
    {
    	$auth = Zend_Auth::getInstance();
    	$methodForm = new Application_Form_Method();
        $methodForm->setName("method");
        $methodForm->setMethod("post");
        $methodForm->setAction("/checkout/onepage/save");
        $order = Doctrine_Query::create()
            ->from('Checkout_Model_Order o')
            ->where("o.reviewed IS NULL")
            ->andWhere("o.user_id=?", $auth->getIdentity()->id)
            ->andWhere("o.session_id=?", Zend_Session::getId())
            ->fetchOne();
            
        if ($order && $order->id)
            $methodForm->setRecord($order);	

        return $methodForm;
    }

    protected function _paymentForm()
    {
    	$auth = Zend_Auth::getInstance();
    	$paymentForm = new Application_Form_Payment();
        $paymentForm->setName("payment");
        $paymentForm->setMethod("post");
        $paymentForm->setAction("/checkout/onepage/save");
        $order = Doctrine_Query::create()->select('*')
            ->from('Checkout_Model_Order o')
            ->where("o.reviewed IS NULL")
            ->andWhere("o.user_id=?", $auth->getIdentity()->id)
            ->andWhere("o.session_id=?", Zend_Session::getId())
            ->fetchOne();
        if ($order && $order->id)
            $paymentForm->setRecord($order);	

        return $paymentForm;
    }

    protected function _reviewForm()
    {
    	$auth = Zend_Auth::getInstance();
    	$reviewForm = new Application_Form_Review();
        $reviewForm->setName("review");
        $reviewForm->setMethod("post");
        $reviewForm->setAction("/checkout/onepage/submit");
        $order = Doctrine_Query::create()->select('*')
            ->from('Checkout_Model_Order o')
            ->where("o.reviewed IS NULL")
            ->andWhere("o.user_id=?", $auth->getIdentity()->id)
            ->andWhere("o.session_id=?", Zend_Session::getId())
            ->fetchOne();
        if ($order && $order->id)
            $reviewForm->setRecord($order);	

        return $reviewForm;
    }

    public function submitAction()
    {
    	$request = $this->getRequest();
	    $checkout = new Zend_Session_Namespace("Checkout");
	    $items = $checkout->cart->getItems();
    	if ($request->isPost() && is_array($items) && count($items)>0):
	    	$itemsSerialized = serialize($items);           
    		$reviewForm = $this->_reviewForm();
    		$order = $reviewForm->getRecord();
    		$incrementId = 1000000 + $order->id;

        	$reviewForm->populate(array(
	        		'cart'=>$itemsSerialized, 
	        		'increment_id'=>$incrementId,
	        		'sub_total'=>$checkout->cart->getSubTotal(),
	        		'shipping'=>$checkout->cart->getShipping(),
	        		'tax'=>$checkout->cart->getTax(),
	        		'grand_total'=>$checkout->cart->getGrandTotal(),
        			'status'=>Checkout_Model_Order::PENDING,
        		)
        	);
        	        	
        	$reviewForm->save();
        	unset($checkout->cart);
        	$this->view->incrementId = $incrementId;
        else :
        	$this->_redirect("/checkout/onepage");
        endif;
    }




}










<?php
class Sales_Model_Sales
{
    public function getOrders()
    {
        $orderTable = Doctrine_Core::getTable('Checkout_Model_Order');
        $q = $orderTable->createQuery('o')->orderBy('date_updated DESC');
        $orders = $q->execute();
        /*$order = $orders->getFirst();
        $user = $order->User;
        $addresses = $user->Addresses;
        $billing = $addresses->get(0);
        $shipping = $addresses->get(1);
        
        var_dump($billing->name);
        var_dump($shipping->name);
        exit();*/
        return $orders;
    }
    
    public function getOrder($id){
    	
        $orderTable = Doctrine_Core::getTable('Checkout_Model_Order');
        $order = $orderTable->find($id);

        return $order;    	
    }
}


<?php

class Checkout_Model_Cart
{
	protected $_items;
	protected $_subTotal;
	protected $_shipping;
	protected $_tax;
	protected $_grandTotal;
	
	public function addToItems($qty, $id, $product){

		if (isset($this->_items[$id]) && $this->_items[$id]>0){
			$qty = $qty + $this->_items[$id]["qty"];
		}
		$this->_items[$id] = array("qty"=>$qty);
		return $id;
	}
	
	public function updateItem($qty, $id){

		if ($qty):
			$this->_items[$id]['qty'] = $qty;
		else :
			unset($this->_items[$id]);
		endif;
		
		return $id;	
	}
	
	public function removeItems(){
		unset($this->_items);
	}
	
	public function getItems(){
		return $this->_items;
	}
	
	public function items(){
		return count($this->_items);
	}
	
	public function getSubTotal(){
		$this->_subTotal = 0.00;
		foreach ($this->_items as $id=>$item): 
			$qty = $item['qty']; 
			$product = Doctrine_Core::getTable('Catalog_Model_Product')->find($id); 
			$this->_subTotal += $qty*$product->price;
		endforeach;
		
		return $this->_subTotal;
	}
	
	public function getShipping(){
		if (!$this->_shipping):
			$methods = Checkout_Model_Method::Methods();
			$shipping = $methods['ground']['rate'];
			$this->setShipping($shipping);
		endif;
		return $this->_shipping;
	}
	
	public function setShipping($shipping){
		$this->_shipping = $shipping;
	}
	
	public function getTax(){
		$rate = Checkout_Model_Tax::RATE;
		$tax = (Checkout_Model_Method::SHIPPING_TAXABLE) 
			? ($this->getSubTotal() + $this->getShipping()) * $rate 
			: $this->getSubTotal() * $rate;
		$tax = number_format($tax,2);
        $this->setTax($tax);
		
		return $this->_tax;
	}
	
	public function setTax($tax){
		$this->_tax = $tax;
	}
	
	public function getGrandTotal(){
		
		$this->_grandTotal = number_format($this->getSubTotal() + $this->getShipping() + $this->getTax(),2);
		return $this->_grandTotal;
	}
	
	public function count($id=NULL){
		$qty = 0;
		if ($id):
			if (is_array($this->_items) && isset($this->_items[$id])):
				$qty = $this->_items[$id]['qty'];
			endif;
		else :
			foreach ($this->_items as $item):
				$qty += $item['qty'];
			endforeach;
		endif;
		
		return $qty;
	}
	

}


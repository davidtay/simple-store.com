<?php

class Checkout_Model_Method
{
	const SHIPPING_TAXABLE = false;
	
	public static function Methods(){
		
		return array(
			'next_day'=> array('label'=>'Next Day','rate'=>'30.00'),
			'two_day'=>array('label'=>'Two Days','rate'=>'15.00'),
			'three_day'=>array('label'=>'Three Days','rate'=>'10.00'),
			'ground'=>array('label'=>'Ground','rate'=>'5.00'),
		);
	}

	
}


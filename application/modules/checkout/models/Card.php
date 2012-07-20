<?php

class Checkout_Model_Card
{
	public static $cards = array(
			'american_express'=> array('label'=>'American Express',),
			'visa'=>array('label'=>'Visa',),
			'mastercard'=>array('label'=>'Mastercard',),
			'discover'=>array('label'=>'Discover',),
		);
	
	public static function Cards(){
		
		return self::$cards;
	}

}


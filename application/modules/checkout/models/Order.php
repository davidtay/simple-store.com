<?php

/**
 * Checkout_Model_Order
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Checkout_Model_Order extends Checkout_Model_Base_Order
{
	const PENDING = 0;
	const PROCESSING = 1;
	const COMPLETED = 2;
	const CANCELLED = 3;
	const SHIPPED = 4;
	
	public static function getStatus($status){
		
		switch ($status):
			case self::PENDING :
				return "Pending";
				break;
			case self::PROCESSING :
				return "Processing";
				break;
			case self::COMPLETED :
				return "Completed";
				break;
			case self::CANCELLED :
				return "Cancelled";
				break;
			case self::SHIPPED :
				return "Shipped";
				break;
			default:
				return "Invalid Status";
				break;
		endswitch;
	}

}
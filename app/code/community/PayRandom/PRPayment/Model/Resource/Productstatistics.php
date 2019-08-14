<?php
class PayRandom_PRPayment_Model_Resource_Productstatistics extends Mage_Core_Model_Resource_Db_Abstract {
	protected function _construct() {
		$this -> _init('prpayment/productstatistics', 'id');
	}
}

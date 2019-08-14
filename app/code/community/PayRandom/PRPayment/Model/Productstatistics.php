<?php
class PayRandom_PRPayment_Model_Productstatistics extends Mage_Core_Model_Abstract {
	protected function _construct() {
		$this -> _init('prpayment/productstatistics');
	}
	
	protected function _beforeSave(){
		if (!$this->getCreatedAt()) {
			$this->setCreatedAt(now());
		}
		$this->setUpdatedAt(now());
		return parent::_beforeSave();
	}
}

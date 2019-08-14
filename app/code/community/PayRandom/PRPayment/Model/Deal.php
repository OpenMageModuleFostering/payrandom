<?php
class PayRandom_PRPayment_Model_Deal extends Mage_Core_Model_Abstract {
	protected function _construct() {
		$this -> _init('prpayment/deal');
	}

	protected function _beforeSave(){
		if (!$this->getCreatedAt()) {
			$this->setCreatedAt(now());
		}
		$this->setUpdatedAt(now());
		return parent::_beforeSave();
	}

	public function getMinimumDiscount(){
		return min($this->getDiscountsArray());
	}

	public function getMaximumDiscount(){
		return max($this->getDiscountsArray());
	}

	public function getDiscount($i){
		$discountsArray = $this->getDiscountsArray();
		if(isset($discountsArray[$i]))
		{
			return $discountsArray[$i];
		}
		return false;
	}

	public function getDiscountsArray(){
		return json_decode($this->getDiscounts());
	}

}

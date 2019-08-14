<?php
class PayRandom_PRPayment_Model_Dealproduct extends Mage_Core_Model_Abstract {
	protected function _construct() {
		$this -> _init('prpayment/dealproduct');
	}

	public function loadByProductId($productId){
		return $this->load($productId, 'product_id');
	}

	protected function _beforeSave(){
		if (!$this->getCreatedAt()) {
			$this->setCreatedAt(now());
		}
		$this->setUpdatedAt(now());
		return parent::_beforeSave();
	}

}

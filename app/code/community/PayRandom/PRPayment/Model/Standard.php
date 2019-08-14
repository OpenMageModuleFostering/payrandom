<?php
class PayRandom_PRPayment_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'prpayment';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
  protected $_canUseForMultishipping  = false;
  protected $_formBlockType = 'prpayment/form_prpayment';

	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		Mage::GetSingleton("core/session")->setFixedPrice($data->getRadioFixedPrice());
		return $this;
	}
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('prpayment/payment/redirect', array('_secure' => true));
	}
}

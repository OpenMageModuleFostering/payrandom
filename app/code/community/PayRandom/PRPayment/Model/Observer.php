<?php
class PayRandom_PRPayment_Model_Observer
{
	public function onUpdate($obj)
	{
		$event = $obj->getEvent();
		$product = $event->getProduct();

		$dealProductModel = Mage::getModel('prpayment/dealproduct')->loadByProductId($product->getId());
		if ($dealProductModel->getId()) {
			$values = array();
			$values['code'] = $product->getId();
			$values['name'] = $product->getName();
			$values['description'] = $product->getName();
      $values['price'] = $product->getPrice();

      /* HACK Disable special price */
      if ($product->getSpecialPrice()) {
        $product->setSpecialPrice(null);
        $product->save();
        if (Mage::getSingleton('core/session')) {
          Mage::getSingleton('core/session')->addNotice(__('No es posible guardar precio especial en productos PayRandom'));
        }
      }

			if(!Mage::helper('prpayment/api')->updateProduct($product->getId(), $values)){
				if(!Mage::helper('prpayment/api')->createProduct($values)){
					throw new Exception(Mage::helper('prpayment')->__('The product cannot be saved to PayRandom'));
				}
			}
		}
	}

	public function onUpdateConfig($obj){
		Mage::log('Update config callback', null, 'payrandom.log');
    if (Mage::getStoreConfig('payment/prpayment/merchant_code')) {
  		Mage::helper('prpayment/api')->installSubscription();
      PayRandom_PRPayment_Helper_Data::updatePredefinedDeals();
      Mage::log('Installed subscription and updated predefined deals', null, 'payrandom.log');
    }
	}

	public function cronTask() {
		PayRandom_PRPayment_Helper_Data::updatePredefinedDeals();
		PayRandom_PRPayment_Helper_Data::updateStatistics();
    }

} 

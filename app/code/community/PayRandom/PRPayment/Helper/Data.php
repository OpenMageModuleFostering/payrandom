<?php
class PayRandom_PRPayment_Helper_Data extends Mage_Payment_Helper_Data {

	const MARGIN_ERROR = 10;
	public static function checkSignature($parameters = null) {
		if (!is_array($parameters)) {
			return false;
		}

		if (!isset($parameters['request_code'])) {
			return false;
		}

		if (!isset($parameters['merchant_code'])) {
			return false;
		}

		if (!isset($parameters['date'])) {
			return false;
		}

		if (!isset($parameters['signature'])) {
			return false;
		}

		$_parameters = $parameters;
		unset($_parameters['signature']);

		$calcSignature = self::calcSignature($_parameters);

		return $calcSignature === $parameters['signature'];
	}

	public static function calcSignature($parameters = null) {
		if (!is_array($parameters))
			return null;

		ksort($parameters);

		return sha1(implode('', $parameters) . Mage::getStoreConfig('payment/prpayment/secret_key'));
	}

	public function getStoreMethods($store = null, $quote = null) {
		$methods = parent::getStoreMethods($store, $quote);

		$canApplyOffer = PayRandom_PRPayment_Helper_Data::checkCartCanApplyOffer();
	
		foreach ($methods as $k => $method) {
			if ($method -> getCode() == 'prpayment') {
				if (!$canApplyOffer) {
					unset($methods[$k]);
				}
			} else {
				if ($canApplyOffer) {
					unset($methods[$k]);
				}
			}
		}

		return $methods;
	}

	public static function idProductsDeal() {
		return Mage::getModel('prpayment/dealproduct')->getCollection()->getColumnValues('product_id');
	}
	
	public static function checkCartCanApplyOffer(){
		$canApplyOffer = true;
		$productsCanApply = PayRandom_PRPayment_Helper_Data::idProductsDeal();
		
		//get a list of product in cart
		$cart = Mage::getSingleton('checkout/session');
		
		$cartProductIds = array();
		foreach ($cart->getQuote()->getAllItems() as $item) {
			$cartProductIds[] = $item -> getProductId();
			if (!in_array($item -> getProductId(), $productsCanApply)) {
				$canApplyOffer = false;
			}
		}
		if(count(array_unique($cartProductIds)) != 1) $canApplyOffer = false;
		return $canApplyOffer;
	}

	public static function getCartDeal(){
		$return = false;
		$productsCanApply = PayRandom_PRPayment_Helper_Data::idProductsDeal();
		
		//get a list of product in cart
		$cart = Mage::getSingleton('checkout/session');
		
		$cartProductIds = array();
		foreach ($cart->getQuote()->getAllItems() as $item) {
			if (in_array($item -> getProductId(), $productsCanApply)) {
				$cartProductIds[] = $item -> getProductId();
			}
		}
		if(count(array_unique($cartProductIds)) == 1){
			$dealProduct = Mage::getModel('prpayment/dealproduct')->loadByProductId(current($cartProductIds));
            $deal = Mage::getModel('prpayment/deal')->load($dealProduct->getDealId());

			$return = $deal;
		}
		return $return;
  }

	public static function getCartProductDeal(){
		$return = false;
		$productsCanApply = PayRandom_PRPayment_Helper_Data::idProductsDeal();
		
		//get a list of product in cart
		$cart = Mage::getSingleton('checkout/session');
		
		$cartProductIds = array();
		foreach ($cart->getQuote()->getAllItems() as $item) {
			if (in_array($item -> getProductId(), $productsCanApply)) {
				$cartProductIds[] = $item -> getProductId();
			}
		}
		if(count(array_unique($cartProductIds)) == 1){
			$dealProduct = Mage::getModel('prpayment/dealproduct')->loadByProductId(current($cartProductIds));
			$return = $dealProduct;
		}
		return $return;
  }

	public static function getProductDeal($productId){
		$return = false;
		
		$dealProduct = Mage::getModel('prpayment/dealproduct')->loadByProductId($productId);
		$deal = Mage::getModel('prpayment/deal')->load($dealProduct->getDealId());
        if ($deal->getId()) {
			$return = $deal;
		}
		return $return;
	}

	public static function updatePredefinedDeals(){
		$return = false;
		$predefinedDeals = Mage::helper('prpayment/api')->predefinedDeals();
		
		if($predefinedDeals !== false){
			$tblName = Mage::getSingleton('core/resource')->getTableName('prpayment/predefineddeal');

			$truncate = Mage::getSingleton('core/resource')->getConnection('core_read');
			$truncate->query("TRUNCATE {$tblName}");


			$i = 0;
			foreach($predefinedDeals as $predefinedDeal){
				$predModel = Mage::getModel('prpayment/predefineddeal');
				
				$predModel->setName($predefinedDeal['name']);

				$predModel->setDiscounts(json_encode($predefinedDeal['discounts']));
				$predModel->save();
			}

			$return  = true;
		}
		return $return;
	}

	public static function updateStatistics(){
		$return = false;

		$statistics = Mage::helper('prpayment/api')->productsStatistics();
		
		if($statistics !== false){
			$tblName = Mage::getSingleton('core/resource')->getTableName('prpayment/productstatistics');

			$truncate = Mage::getSingleton('core/resource')->getConnection('core_read');
			$truncate->query("TRUNCATE {$tblName}");

			foreach($statistics as $statistic){
				$statModel = Mage::getModel('prpayment/productstatistics');
				
				$statModel->name = $statistic['name'];
				$statModel->deal_name = $statistic['deal_name'];
				$statModel->product_id = $statistic['code'];
				$statModel->total_amount = $statistic['total_amount'];
				$statModel->times_purchased = $statistic['times_purchased'];
				$statModel->units = $statistic['units'];
				$statModel->min_price = $statistic['min_price'];
				$statModel->max_price = $statistic['max_price'];
				$statModel->expected_average_price = $statistic['expected_average_price'];
				$statModel->average_price = $statistic['average_price'];
				$statModel->start_date = $statistic['start_date'];
				$statModel->end_date = $statistic['end_date'];
				$statModel->active = $statistic['active'];
				
				$statModel->save();
			}

			$return  = true;
		}
		return $return;
	}

	public static function getDiscountsAvg($discounts){
		if(!is_array($discounts) || count($discounts) < 1)
			return false;

		return array_sum($discounts)/count($discounts);
	}

	public static function getArrDiscountsAvg($discounts){
		$return = array();
		$discountAvg = self::getDiscountsAvg($discounts);

		foreach($discounts as $discount){
			$return[] = 1 - $discount / $discountAvg;
		}

		return $return;
	}

	public static function converToPercent($arrData){
		return array_map(array('PayRandom_PRPayment_Helper_Data', 'toPercent'), $arrData);
	}

	public static function toPercent($val){
		return $val * 100;
	}

	public static function toPow2($val){
		return $val * $val;
	}

	public static function calcBalance($arrData, $avgPrice){
 		return 2 * self::calcVariance(self::applyDiscountArray($arrData, $avgPrice)) / self::MARGIN_ERROR;
	}

	public static function calcVariance($data){
		$avg = self::getDiscountsAvg($data);

		$dataPow2 = array_map(array('PayRandom_PRPayment_Helper_Data', 'toPow2'), $data);
		return array_sum($dataPow2) / count($data) - $avg * $avg;
	}

	public static function calcAttraction($arrData, $avgPrice){
		$discountsPerCent = self::converToPercent($arrData);

		$num100 = 0;
		foreach($discountsPerCent as $discount){
			if($discount == 100) $num100++;
		}

		$correction = 0;
		switch($num100){
			case 0:
				$correction = 0;
				break;
			case 1:
				$correction = 10;
				break;
			case 2:
				$correction = 30;
				break;
			case 3:
				$correction = 50;
				break;
			default:
				$correction = 100;
				break;
    }
  		return self::calcVariance(self::applyDiscountArray($arrData, $avgPrice)) + $correction;
  	}

	public static function deserializeDiscounts($discounts){
		return json_decode($discounts, true);
	}

	public static function applyDiscountArray($discountArray, $price){
		$return = array();
		foreach($discountArray as $discount){
			$return[] = self::applyDiscount($discount, $price);
		}
		return $return;
	}

	public static function applyDiscount($discount, $price){
		$discount = (float) $discount;
		return $price - $price * $discount;
	}
}

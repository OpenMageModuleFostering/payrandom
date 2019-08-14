<?php 
	class PayRandom_PRPayment_Helper_Data_Api extends Mage_Core_Helper_Abstract {
		const API_URL = 'http://payrandom.zlworks.com/api';
		const API_VERSION = '/v1';

		public static function createProduct($values){
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/products',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $values,
			));

			$resp = curl_exec($curl);
			curl_close($curl);

			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('createProduct:: ' . var_export($resp, true), null, 'payrandom.log');
			}
			return $return['code'] == 1;
		}

		public static function updateProduct($productId, $values){
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/products' . '/' . urlencode($productId),
				CURLOPT_CUSTOMREQUEST => 'PUT',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $values,
			));
			
			$resp = curl_exec($curl);
			curl_close($curl);

			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('updateProduct:: ' . var_export($resp, true), null, 'payrandom.log');
			}
			return $return['code'] == 1;
		}

		public static function updateDealFromModel($deal, $productId){
			$values = array();
			$values['product_code'] = $productId;
			
			$values['name'] = $deal->getName();
			$values['discounts'] = $deal->getDiscounts();
			$values['kind'] = $deal->getKind();
			$values['view_format'] = $deal->getViewFormat();
			
			if($deal->getId()){
				return self::updateDeal($deal->getId(), $values);
			}else{
				return self::createDeal($values);
			}
		}

		public static function removeProduct($productId){
			$values = array();
			$values['code'] = $productId;
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/products' . '/' . urlencode($productId),
				CURLOPT_CUSTOMREQUEST => 'DELETE',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $values,
			));

			$resp = curl_exec($curl);
			curl_close($curl);
			
			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('removeProduct:: ' . var_export($resp, true), null, 'payrandom.log');
			}
			return $return['code'] == 1;
		}

		public static function createDeal($values){
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/deals',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $values,
			));

			$resp = curl_exec($curl);
			curl_close($curl);
			
			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('createDeal:: ' . var_export($resp, true), null, 'payrandom.log');
			}
			return $return['code'] == 1;
		}

		public static function updateDeal($dealId, $values){
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/deals' . '/' . urlencode($dealId),
				CURLOPT_CUSTOMREQUEST => 'PUT',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $values,
			));
			
			$resp = curl_exec($curl);
			curl_close($curl);

			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('updateDeal:: ' . var_export($resp, true), null, 'payrandom.log');
			}
			return $return['code'] == 1;
		}

		public static function installSubscription(){
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/merchants/' . Mage::getStoreConfig('payment/prpayment/merchant_code') . '/install_subscription',
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $values,
			));
			
			$resp = curl_exec($curl);
			curl_close($curl);

			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('installSubscription:: ' . var_export($resp, true), null, 'payrandom.log');
			}
			return $return['code'] == 1;
		}

		public static function predefinedDeals(){
			$values = array();
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/predefined_deals?'. http_build_query($values),
			));
			
			$resp = curl_exec($curl);
			curl_close($curl);

			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('predefinedDeals:: ' . var_export($resp, true), null, 'payrandom.log');
				return false;
			}else{
				return $return['predefined_deals'];
			}
		}

		public static function productsStatistics(){
			$values = array();
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => self::API_URL . self::API_VERSION . '/merchants/' . Mage::getStoreConfig('payment/prpayment/merchant_code') . '/products?'. http_build_query($values),
			));

			$resp = curl_exec($curl);
			curl_close($curl);

			$return = json_decode($resp, true);
			if($return['code'] != 1){
				Mage::log('productsStatistics:: ' . var_export($resp, true), null, 'payrandom.log');
				return false;
			}else{
				return $return['products'];
			}
		}

		public static function getOrdersUrl(){
			return self::API_URL . self::API_VERSION . '/orders';
		}

		public static function getDashBoardUrl(){
			$values = array();
			$values['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$values['date'] = date('Y-m-d_H_i_s');
			$values['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			$values['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($values);

      if ($values['merchant_code']) {
  			return self::API_URL . self::API_VERSION . '/merchants/' . Mage::getStoreConfig('payment/prpayment/merchant_code') . '/statistics?' . http_build_query($values);
      }
      else {
  			return self::API_URL . self::API_VERSION . '/merchants/no_merchant_code';
      }
		}


		public static function getRouletteImg($discounts, $productPrice, $freeText, $width, $height){
			$params = array();

			$disc = array();
			foreach($discounts as $discount){
				$disc[] = (1 - $discount) * $productPrice;
			}
			$disc = json_encode($disc);

			$params['sizes'] = $width . 'x'. $height;
			$params['prices'] = $disc;
			$params['free_text'] = $freeText;
			
			return self::API_URL . self::API_VERSION . '/roulette?' . http_build_query($params);
		}

		public static function getWatermarkImg($upperText, $offerText, $width, $height){
			$params = array();

			$params['sizes'] = $width . 'x'. $height;
			$params['upper_text'] = $upperText;
			$params['offer_text'] = $offerText;
			
			return self::API_URL . self::API_VERSION . '/watermark?' . http_build_query($params);
		}
	}

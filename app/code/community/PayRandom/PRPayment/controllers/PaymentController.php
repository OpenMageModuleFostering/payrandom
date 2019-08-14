<?php
/**
 * @category    PayRandom
 * @package     PayRandom_PRPayment
 * @copyright   Copyright (c) 2013 PayRandom (http://www.payrandom.com)
 */


class PayRandom_PRPayment_PaymentController extends Mage_Core_Controller_Front_Action {
	// The redirect action is triggered when someone places an order
	public function redirectAction() {
		$this->loadLayout();
		
		$_order = new Mage_Sales_Model_Order();
		$orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		if($orderId){
      $_order->loadByIncrementId($orderId);
			
			$parameters = array();
			$parameters['merchant_code'] = Mage::getStoreConfig('payment/prpayment/merchant_code');
			$parameters['request_code'] = date('YmdHis') . str_pad(rand(), 10, "0", STR_PAD_LEFT);
			foreach($_order->getAllItems() as $item){
				$parameters['product_code'] = $item->getProductId();
				$parameters['quantity_ordered'] = $item->getQtyOrdered();
			}
			
			$dealProductModel = Mage::getModel('prpayment/dealproduct')->loadByProductId($parameters['product_code']);
			if ($dealProductModel->getId()) {
				$parameters['deal_code'] = $dealProductModel->getDealId();
			}
			
			$parameters['date'] = date('Y-m-d_H_i_s');
			$parameters['order_code'] = $orderId;
			$parameters['url_notification'] = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('prpayment/payment/notify', array('_secure' => true)));
			$parameters['url_ok'] = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('prpayment/payment/resultok', array('_secure' => true)));
			$parameters['url_ko'] = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('prpayment/payment/resultko', array('_secure' => true)));
			$parameters['total_amount'] = $_order->getBaseGrandTotal();
      $parameters['total_amount_for_discount'] = $_order->getBaseGrandTotal() - $_order->getShippingAmount();

      if (Mage::GetSingleton("core/session")->getFixedPrice() == 2) {
        $parameters['apply_fixed_discount'] = 1;
      }
			
			$parameters['signature'] = PayRandom_PRPayment_Helper_Data::calcSignature($parameters);
			
			Mage::log('redirectAction:: Parameters: ' . var_export($parameters, true), null, 'payrandom.log');

			$block = $this->getLayout()->createBlock('Mage_Core_Block_Template','prpayment',array('template' => 'prpayment/redirect.phtml'));
			$block->setParameters($parameters);
			
			$this->getLayout()->getBlock('content')->append($block);
			$this->renderLayout();
		}else{
			Mage_Core_Controller_Varien_Action::_redirect('');
		}
	}
	
	public function notifyAction() {
		$response = array();
		$response['result'] = '0';
		if($this->getRequest()->isPost()) {
			if(PayRandom_PRPayment_Helper_Data :: checkSignature($this->getRequest()->getParams())){
				if($this->checkParams($this->getRequest()->getParams())){
					$orderId = $this->getRequest()->getParam('order_code');
					$order = Mage::getModel('sales/order');
					$order->loadByIncrementId($orderId);
					if($order->getState() == Mage_Sales_Model_Order::STATE_NEW){ // Check current state
						$resultPR = $this->getRequest()->getParam('result');
						if($resultPR == '1'){
							$discountAmount = $this->getRequest()->getParam('discount');
							if($discountAmount){
								$order->setDiscountAmount(-$discountAmount);
								$order->setBaseDiscountAmount(-$discountAmount);
								$order->setDiscountDescription('PayRandom');
								$order->setGrandTotal($order->getGrandTotal()-$discountAmount);
								$order->setBaseGrandTotal($order->getBaseGrandTotal()-$discountAmount);
								$order->setSubtotalWithDiscount($order->getSubtotalWithDiscount()-$discountAmount);
								$order->setBaseSubtotalWithDiscount($order->getBaseSubtotalWithDiscount()-$discountAmount);
							}
							
							$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.');
							
							$order->sendNewOrderEmail();
							$order->setEmailSent(true);
							
							$order->save();
							
							Mage::log('notifyAction::Complete. Parameters: ' . var_export($this->getRequest()->getParams(), true), null, 'payrandom.log');
              PayRandom_PRPayment_Helper_Data::updateStatistics();
							$response['result'] = '1';
						}else{
							Mage::log('notifyAction::Cancelled (' . $resultPR . '). Parameters: ' . var_export($this->getRequest()->getParams(), true), null, 'payrandom.log');
							$order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
						}
					}else{
						Mage::log('notifyAction::Current state incorrect: ' . var_export($this->getRequest()->getParams(), true), null, 'payrandom.log');
						$response['result'] = '903';
					}
				}else{
					Mage::log('notifyAction::Missing parameters. Parameters: ' . var_export($this->getRequest()->getParams(), true), null, 'payrandom.log');
					$response['result'] = '902';
				}
			}else{
				Mage::log('notifyAction::Error validating signature. Parameters: ' . var_export($this->getRequest()->getParams(), true), null, 'payrandom.log');
				$response['result'] = '901';
			}
		} else {
			Mage::log('notifyAction::Is not a POST request. Parameters: ' . var_export($this->getRequest()->getParams(), true), null, 'payrandom.log');
			$response['result'] = '900';
		}
		
		$this->getResponse()->setHeader('Content-type', 'application/json');
	    $this->getResponse()->setBody(json_encode($response));
	}

	public function resultokAction() {
		Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
	}

	public function resultkoAction() {
		Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
	}
	
	private function checkParams($params){
		if(!isset($params['result'])){
			Mage::log('checkParams::No result. Parameters: ' . var_export($params, true), null, 'payrandom.log');
			return false;
		}
		if(!isset($params['order_code'])){
			Mage::log('checkParams::No order_code. Parameters: ' . var_export($params, true), null, 'payrandom.log');
			return false;
		}
		return true;
	}
}

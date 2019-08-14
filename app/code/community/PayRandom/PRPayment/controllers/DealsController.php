<?php
/**
 * @category    PayRandom
 * @package     PayRandom_PRPayment
 * @copyright   Copyright (c) 2013 PayRandom (http://www.payrandom.com)
 */


 class PayRandom_PRPayment_DealsController extends Mage_Adminhtml_Controller_Action {
	public function indexAction()
    {
	    $this->loadLayout();
	    $this->renderLayout();
    }

	public function chooseAction()
    {
		$this->loadLayout();
		$this->_setActiveMenu('prpayment_menu');
		$this->_addBreadcrumb('Pay Random Deal', 'Pay Random Deal');
		$this->_addContent($this->getLayout()->createBlock('prpayment/adminhtml_deals_choose'));
		$this->renderLayout();
    }

     public function chooseredirAction()
	   {
	   		$productId = $this->getRequest()->getParam('product_id');
	   		$avgPrice = $this->getRequest()->getParam('avg_price');
	   		$this->_redirect('*/deals/choose', array('product_id' => $productId, 'avg_price' => $avgPrice));
	   }
	
	public function editAction()
      {
			$dealModel = Mage::getModel('prpayment/deal');
			$dealProductModel = Mage::getModel('prpayment/dealproduct');

			$productId = $this->getRequest()->getParam('product_id');

			$dealProductModel->loadByProductId($productId);
			$dealModel->load($dealProductModel->getDealId());

			if ($productId != 0)
			{
				Mage::register('deals_data', $dealModel);
				Mage::register('deals_product_id', $productId);
				
				$this->loadLayout();
				$this->_setActiveMenu('prpayment_menu');
				$this->_addBreadcrumb('Pay Random Deal', 'Pay Random Deal');
				
				$this->getLayout()->getBlock('head')
					->setCanLoadExtJs(true);
				$this->_addContent($this->getLayout()
					->createBlock('prpayment/adminhtml_deals_edit')
				);

				$this->renderLayout();

			}
           else
           {
                 Mage::getSingleton('adminhtml/session')
                       ->addError('No editable');
                 $this->_redirect('*/*/');
            }
       }

       public function newAction()
       {
       		$productId = $this->getRequest()->getParam('product_id');
       		$this->_redirect('*/deals/edit', array('product_id' => $productId));
       }

       public function assignAction(){
       		$productId 		  = $this->getRequest()->getParam('product_id');
       		$predefinedDealId = $this->getRequest()->getParam('predefined_deal_id');
          $avgPrice = $this->getRequest()->getParam('avg_price');
          $hasFixedPrice = $this->getRequest()->getParam('radioFixedPrice');
          $fixedPrice = $this->getRequest()->getParam('fixed_price');

          $predefinedDeal = Mage::getModel('prpayment/predefineddeal');
          $predefinedDeal->load($predefinedDealId);

          $avgDiscounts =  PayRandom_PRPayment_Helper_Data::deserializeDiscounts($predefinedDeal->getDiscounts());

          $finalPrices = PayRandom_PRPayment_Helper_Data::applyDiscountArray($avgDiscounts, $avgPrice);


          $product = Mage::getModel('catalog/product')->load($productId); 

          $realDiscounts = array();
          foreach($finalPrices as $finalPrice){
            $realDiscounts[] = 1 - ($finalPrice / $product->getPrice());
          }

          if ($hasFixedPrice == 1) {
            $fixedDiscount = 1 - ($fixedPrice / $product->getPrice());
            return $this->_saveAction($productId, $realDiscounts, $fixedDiscount, $predefinedDeal->getName(), 'prices');
          }
          else {
            return $this->_saveAction($productId, $realDiscounts, null, $predefinedDeal->getName(), 'prices');
          }

       }

       public function saveAction(){
       		$productId = $this->getRequest()->getParam('deals_product_id');
       		$dealName = $this->getRequest()->getParam('deal_name');
          $viewFormat = $this->getRequest()->getParam('view_format');
          $discounts = $this->getRequest()->getParam('discounts');
          $hasFixedPrice = $this->getRequest()->getParam('radioFixedPrice');

          if ($hasFixedPrice == 1) {
            $product = Mage::getModel('catalog/product')->load($productId);
            $fixedPrice = $this->getRequest()->getParam('fixed_price');
            $fixedDiscount = 1 - ($fixedPrice / $product->getPrice());
          }

          return $this->_saveAction($productId, $discounts, $fixedDiscount, $dealName, $viewFormat);
       }

       private function discountsToFloat($val){
       		return (float) $val;
       }

       private function _saveAction($productId, $discounts, $fixedDiscount, $dealName, $viewFormat){
       		$discounts = array_map(array('PayRandom_PRPayment_DealsController', 'discountsToFloat'), $discounts);
       		$dealModel = Mage::getModel('prpayment/deal');
			$dealProductModel = Mage::getModel('prpayment/dealproduct');

			$dealProductModel->loadByProductId($productId);
			$dealModel->load($dealProductModel->getDealId());

			if ($productId != 0)
			{
				try {
					$product = Mage::getModel('catalog/product')->load($productId); 
						if($dealName == ''){
							$dealName = $product->getName();
						}


					$values = array();
					$values['code'] = $productId;
					$values['name'] = $product->getName();
					$values['description'] = $product->getName();
					$values['price'] = $product->getPrice();

					if($dealProductModel->getId() == 0){
						if(!Mage::helper('prpayment/api')->createProduct($values)){
							if(!Mage::helper('prpayment/api')->updateProduct($productId, $values)){
								throw new Exception(Mage::helper('prpayment')->__('The product cannot be saved to PayRandom'));
							}
						}
					}else{
						if(!Mage::helper('prpayment/api')->updateProduct($productId, $values)){
							if(!Mage::helper('prpayment/api')->createProduct($values)){
								throw new Exception(Mage::helper('prpayment')->__('The product cannot be saved to PayRandom'));
							}
						}
					}

					$dealModel->setName($dealName);
					$dealModel->setDiscounts(json_encode($discounts));
					$dealModel->setKind('custom');
          $dealModel->setViewFormat($viewFormat);

					$dealModel->setFixedDiscount($fixedDiscount);
					
					$values = array();
					$values['product_code'] = $productId;
					
					$values['code'] = $dealModel->getId();
					$values['name'] = $dealModel->getName();
					$values['discounts'] = $dealModel->getDiscounts();
          $values['kind'] = $dealModel->getKind();

          if (!is_null($dealModel->getFixedDiscount())) {
            $values['fixed_discount'] = $dealModel->getFixedDiscount();
          }
				
           /* var_dump($values);
          die();*/
					if($dealModel->getId() == 0){
						$dealModel->save();
            $values['code'] = $dealModel->getId();
						if(!Mage::helper('prpayment/api')->createDeal($values)){
							$dealModel->delete();
							throw new Exception(Mage::helper('prpayment')->__('The deal cannot be saved to PayRandom'));
						}
					}else{
						if(!Mage::helper('prpayment/api')->updateDeal($dealModel->getId(), $values)){
							throw new Exception(Mage::helper('prpayment')->__('The deal cannot be saved to PayRandom'));
						}
						$dealModel->save();
					}
					
 					if($dealProductModel->getId() == 0){
						$dealProductModel->setProductId($productId);
						$dealProductModel->setDealId($dealModel->getId());
						$dealProductModel->save();
					}

					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('prpayment')->__('Deal was successfully saved'));
					$this->getResponse()->setRedirect($this->getUrl('*/deals/edit', array('product_id' => $productId)));
					return;
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('product_id' => $productId)));
					return;
				}
			}
			else
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('prpayment')->__('No product id'));
				$this->getResponse()->setRedirect($this->getUrl('*/catalog_product'));
				return;
			}
       }

       public function removeAction()
	    {
	    	$productId = $this->getRequest()->getParam('product_id');

	    	$dealModel = Mage::getModel('prpayment/deal');
			$dealProductModel = Mage::getModel('prpayment/dealproduct');

			$dealProductModel->loadByProductId($productId);
			$dealModel->load($dealProductModel->getDealId());

			if(Mage::helper('prpayment/api')->removeProduct($productId)){
				$dealProductModel->delete();
				$dealModel->delete();
				Mage::getSingleton('core/session')->addSuccess($this->__('The deal has been deleted successfully'));
			}else{
				Mage::getSingleton('core/session')->addError($this->__('The deal hasn\'t been deleted'));
			}

			$this->_redirect('*/catalog_product/index');
	    }

	}
	

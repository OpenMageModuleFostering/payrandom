<?php
    class PayRandom_PRPayment_Block_Adminhtml_Deals_Edit extends Mage_Adminhtml_Block_Widget_Container{
         protected function _prepareLayout()
           {
              $this->setTemplate('prpayment/deal/design.phtml');

              $productId = $this->getRequest()->getParam('product_id');

              $product = Mage::getModel('catalog/product')->load($productId);

              if ( Mage::registry('deals_data') && Mage::registry('deals_data')->getId() )
              {
                $dealData = Mage::registry('deals_data');
                $this->setDeal($dealData);
              } else {
                $this->setDeal(Mage::getModel('prpayment/deal'));
              }


              $this->setProduct($product);
              $this->setAvgPrice($product->getPrice());

              if($this->getRequest()->getParam('avg_price')){
                $this->setAvgPrice($this->getRequest()->getParam('avg_price'));
              }
              return parent::_prepareLayout();
           }
    }
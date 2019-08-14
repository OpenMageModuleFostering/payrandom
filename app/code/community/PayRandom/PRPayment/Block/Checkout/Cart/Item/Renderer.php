<?php class PayRandom_PRPayment_Block_Checkout_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{
	 /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getTemplate()) {
            return '';
        }
		if(PayRandom_PRPayment_Helper_Data::checkCartCanApplyOffer()){
           $productId = $this->getItem()->getProduct()->getId();

            $dealProduct = Mage::getModel('prpayment/dealproduct')->loadByProductId($productId);
            $deal = Mage::getModel('prpayment/deal')->load($dealProduct->getDealId());

            $this->setMinimumDiscount($deal->getMinimumDiscount());
            $this->setMaximumDiscount($deal->getMaximumDiscount());

            if($this->getTemplate() == 'checkout/cart/item/default.phtml'){
    			$this->setTemplate('prpayment/checkout/cart/item/default.phtml');
            }elseif($this->getTemplate() == 'checkout/onepage/review/item.phtml'){
                $this->setTemplate('prpayment/checkout/onepage/review/item.phtml');
            }
        }
		
		$html = $this->renderView();
        return $html;
    }
	
}
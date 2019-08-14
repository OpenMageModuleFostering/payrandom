<?php class PayRandom_PRPayment_Block_Checkout_Onepage_Shipping_Method extends Mage_Checkout_Block_Onepage_Shipping_Method
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
			$this->setTemplate('prpayment/checkout/onepage/shipping_method.phtml');
        }
		
		$html = $this->renderView();
        return $html;
    }
	
	
}

<?php class PayRandom_PRPayment_Block_Checkout_Onepage_Payment extends Mage_Checkout_Block_Onepage_Payment
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
			$this->setTemplate('prpayment/checkout/onepage/payment.phtml');
        }
		
		$html = $this->renderView();
        return $html;
    }
	
	
}

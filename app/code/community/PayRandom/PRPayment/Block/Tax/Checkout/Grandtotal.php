<?php class PayRandom_PRPayment_Block_Tax_Checkout_Grandtotal extends Mage_Tax_Block_Checkout_Grandtotal
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
            $this->setCartDeal(PayRandom_PRPayment_Helper_Data::getCartDeal());
			$this->setTemplate('prpayment/tax/checkout/grandtotal.phtml');
        }
		
		$html = $this->renderView();
        return $html;
    }
	
}
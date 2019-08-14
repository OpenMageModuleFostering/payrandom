<?php class PayRandom_PRPayment_Block_Checkout_Cart extends Mage_Checkout_Block_Cart
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
			$this->setTemplate('prpayment/checkout/cart.phtml');
        }
		
		$html = $this->renderView();
        return $html;
    }
	
}
<?php class PayRandom_PRPayment_Block_Checkout_Onepage_Review_Info extends Mage_Checkout_Block_Onepage_Review_Info
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
			$this->setTemplate('prpayment/review.phtml');
        }
		
		$html = $this->renderView();
        return $html;
    }
	
	
}

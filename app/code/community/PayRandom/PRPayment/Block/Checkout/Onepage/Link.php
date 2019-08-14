<?php class PayRandom_PRPayment_Block_Checkout_Onepage_Link extends Mage_Checkout_Block_Onepage_Link
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
            $this->setTemplate('prpayment/checkout/onepage/link.phtml');
        }

        $html = $this->renderView();
        return $html;
    }
	
}

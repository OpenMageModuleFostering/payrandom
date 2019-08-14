<?php class PayRandom_PRPayment_Block_Product_Price extends Mage_Catalog_Block_Product_Price
{
    // and Override _toHtml Function to be 
    protected function _toHtml()
    {
    	$productId = null;
		if($this->getProduct()){
			$productId = $this->getProduct()->getId();
		}
    	$productsCanApply = PayRandom_PRPayment_Helper_Data::idProductsDeal();
        if(in_array($productId, $productsCanApply)){
            $productDeal = PayRandom_PRPayment_Helper_Data::getProductDeal($productId);
            $this->setProductDeal($productDeal);
            $this->setTemplate('prpayment/price.phtml');
        }

        if (!$this->getProduct() || $this->getProduct()->getCanShowPrice() === false) {
            return '';
        }
        return parent::_toHtml();
    }
}
<?php
class PayRandom_PRPayment_Block_Product_View extends Mage_Catalog_Block_Product_View {
	public function getTierPriceHtml($product = null)
    {
    	$productId = null;
        if (is_null($product)) {
            if($product = $this->getProduct()){
            	$productId = $product->getId();
            }
        }
		$productsCanApply = PayRandom_PRPayment_Helper_Data::idProductsDeal();
        if(in_array($productId, $productsCanApply)){
			return '';
        }

        return $this->_getPriceBlock($product->getTypeId())
            ->setTemplate($this->getTierPriceTemplate())
            ->setProduct($product)
            ->setInGrouped($this->getProduct()->isGrouped())
            ->toHtml();
    }

}


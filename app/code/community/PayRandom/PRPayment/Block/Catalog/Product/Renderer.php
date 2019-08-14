<?php
class PayRandom_PRPayment_Block_Catalog_Product_Renderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
    $productsCanApply = PayRandom_PRPayment_Helper_Data::idProductsDeal();
    $product = Mage::getModel('catalog/product')->load($row->getId());

		if (in_array( $row->getId(), $productsCanApply)) {
			$value = $this->htmlEscape($this->__('Edit PayRandom Roulette')) ;
			$img = '<img src="' . $this->getSkinUrl('images/prpayment/edit.png') . '" alt="' . $value . '" />';
			$return = '<a href="' . Mage::helper("adminhtml")->getUrl('*/deals/edit', array('product_id' => $row->getId())) .'">' . $img . '</a>';

			$value = $this->htmlEscape($this->__('Delete PayRandom Roulette')) ;
			$img = '<img src="' . $this->getSkinUrl('images/prpayment/delete.png') . '" alt="' . $value . '" />';
			$return .= '<a href="' . Mage::helper("adminhtml")->getUrl('*/deals/remove', array('product_id' => $row->getId())) .'" onclick="return confirm(\'' . $this->__('¿Estás seguro de que quieres eliminar la oferta PayRandom?') . '\');">' . $img . '</a>';

			return $return;
    }else{
      if ($row->getTypeId() == "simple" && !$product->getSpecialPrice()) {
        $value = $this->htmlEscape($this->__('Add PayRandom Roulette')) ;
        $img = '<img src="' . $this->getSkinUrl('images/prpayment/add.png') . '" alt="' . $value . '" />';
        return '<a href="' . Mage::helper("adminhtml")->getUrl('*/deals/choose', array('product_id' => $row->getId())) .'">' . $img . '</a>';
      }
		}
	}

}

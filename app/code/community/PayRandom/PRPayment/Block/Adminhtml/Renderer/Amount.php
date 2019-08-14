<?php

class PayRandom_PRPayment_Block_Adminhtml_Renderer_Amount extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
      $value = $row->getData($this->getColumn()->getIndex());
    	return $this->helper('checkout')->formatPrice($value);
    }
}

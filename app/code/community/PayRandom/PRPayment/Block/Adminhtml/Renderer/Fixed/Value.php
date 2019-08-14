<?php

class PayRandom_PRPayment_Block_Adminhtml_Renderer_Fixed_Value extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function __construct($fixedValue = 0)
	{
		parent::__construct();
		$this->setFixedValue($fixedValue);
	}

    public function render(Varien_Object $row)
    {
    	return $this->helper('checkout')->formatPrice($this->getFixedValue());
    }
}

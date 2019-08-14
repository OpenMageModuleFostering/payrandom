<?php

class PayRandom_PRPayment_Block_Adminhtml_Renderer_Balance extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{  	
    public function __construct($avgPrice)
    {
      parent::__construct();
      $this->setAvgPrice($avgPrice);
    }

    public function render(Varien_Object $row)
    {
	   	$data = $row->getData($this->getColumn()->getIndex());

    	$jsonData = json_decode($data, true);
      $avgPrice = $this->getAvgPrice();

      return number_format(PayRandom_PRPayment_Helper_Data::calcBalance($jsonData, $avgPrice));
    }
}

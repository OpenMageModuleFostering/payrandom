<?php

class PayRandom_PRPayment_Block_Adminhtml_Renderer_Attraction extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
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
		$attraction = PayRandom_PRPayment_Helper_Data::calcAttraction($jsonData, $avgPrice);

		if($attraction < 10){
			$numberOfImg = 1;
			$imgAttraction = $this->getSkinUrl('images/prpayment/attraction_medium.png');
			$altText = Mage::helper('prpayment')->__('Atracción Media');
		}elseif($attraction < 150){
			$numberOfImg = 2;
			$imgAttraction = $this->getSkinUrl('images/prpayment/attraction_high.png');
			$altText = Mage::helper('prpayment')->__('Atracción Alta');
		}else{
			$numberOfImg = 3;
			$imgAttraction = $this->getSkinUrl('images/prpayment/attraction_very_high.png');
			$altText = Mage::helper('prpayment')->__('Atracción Muy Alta');
		}
    	return str_repeat('<img src="' . $imgAttraction . '" alt="' . $altText . '" /> ', $numberOfImg);
    }
}

<?php

class PayRandom_PRPayment_Block_Adminhtml_Renderer_Discount extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function __construct($discountPos = 0)
	{
		parent::__construct();
		$this->setDiscountPos($discountPos);
	}

    public function render(Varien_Object $row)
    {
    	$data = $row->getData($this->getColumn()->getIndex());

    	$jsonData = json_decode($data, true);

		if($this->getAvgPrice()){
    		$diffOverAvg = $jsonData[$this->getDiscountPos()];

			$price = PayRandom_PRPayment_Helper_Data::applyDiscount($diffOverAvg, $this->getAvgPrice());

			$style = '';

			$diffOverAvg = -$diffOverAvg;

			if($diffOverAvg > 0){
				$style = 'background-color: #ffe5e6;';
			}elseif($diffOverAvg < 0){
				$style = 'background-color: #e8f3e5;';
			}

			$return = '<span style="font-size: 0.8em;">' . ($diffOverAvg >= 0?'+':'') . (number_format($diffOverAvg * 100, 2)) . '%' . '</span>';
			$return .= '<br/>';
			$return .= $this->helper('checkout')->formatPrice($price);

			return '<div style="' . $style . 'width:100%">' . $return . '</div>';
    	}else{
    		return $jsonData[$this->getDiscountPos()] * 100 . '%';
    	}
    }
}

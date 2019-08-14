<?php

class PayRandom_PRPayment_Block_Adminhtml_Renderer_Option extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
    	$data = $row->getData($this->getColumn()->getIndex());
    	$productId = $this->getRequest()->getParam('product_id');
    	
    	$assignUrl = Mage::helper("adminhtml")->getUrl('*/deals/assign', array('product_id' => $productId, 'predefined_deal_id' => $data));
    	return '<input type="radio" name="' . $this->getColumn()->getIndex() . '" value="' . $data . '" title="' . $assignUrl . '" />';
    }
}

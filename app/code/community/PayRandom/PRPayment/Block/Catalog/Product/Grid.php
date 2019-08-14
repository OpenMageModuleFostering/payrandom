<?php

class PayRandom_PRPayment_Block_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid {
	protected function _prepareColumns() {
		parent::_prepareColumns();
		$this->addColumn('addPayRandom',
            array(
                'header'    => Mage::helper('prpayment')->__('PayRandom'),
                'width'     => '50px',
                'filter'    => false,
                'sortable'  => false,
                'renderer'  => 'PayRandom_PRPayment_Block_Catalog_Product_Renderer',
        ));
		return $this;
	}

}

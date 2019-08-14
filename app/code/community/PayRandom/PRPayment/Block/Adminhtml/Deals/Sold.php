<?php
class PayRandom_PRPayment_Block_Adminhtml_Deals_Sold extends Mage_Adminhtml_Block_Widget_Grid_Container{
   
    public function __construct()
        {
         $this->_blockGroup = 'prpayment';
         $this->_controller = 'adminhtml_deals_sold';

         $this->_headerText = Mage::helper('prpayment')->__('Productos PR vendidos');
         

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick' => 'javascript: history.go(-1)',
            'class'     => 'back',
        ));

        parent::__construct();
        $this->removeButton('add');

    }
}
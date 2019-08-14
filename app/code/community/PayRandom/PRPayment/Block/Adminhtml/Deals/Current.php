<?php
class PayRandom_PRPayment_Block_Adminhtml_Deals_Current extends Mage_Adminhtml_Block_Widget_Grid_Container{
   
    public function __construct()
        {
         $this->_blockGroup = 'prpayment';
         $this->_controller = 'adminhtml_deals_current';

         $this->_headerText = Mage::helper('prpayment')->__('Productos PR en juego');
         

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick' => 'javascript: history.go(-1)',
            'class'     => 'back',
        ));

        parent::__construct();
        $this->removeButton('add');

    }
}
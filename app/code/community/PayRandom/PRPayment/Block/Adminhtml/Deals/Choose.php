<?php
class PayRandom_PRPayment_Block_Adminhtml_Deals_Choose extends Mage_Adminhtml_Block_Widget_Grid_Container{
   
    public function __construct()
        {
         $this->_blockGroup = 'prpayment';
         $this->_controller = 'adminhtml_deals_choose';

         $this->_headerText = Mage::helper('prpayment')->__('Asignar ruleta PayRandom');
         

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick' => 'javascript: history.go(-1)',
            'class'     => 'back',
        ));

        $this->_addButton('assign', array(
            'label'     => Mage::helper('prpayment')->__('Asignar ruleta'),
            'onclick'   => 'prPaymentAssignPredefined()',
            'class'     => 'assign',
        ));

        $this->_addButton('add', array(
            'label'     => Mage::helper('prpayment')->__('Nueva ruleta'),
            'onclick'   => 'setLocation(\'' . $this->getCreateUrl() .'\')',
            'class'     => 'add',
        ));

         parent::__construct();
    }

    public function getCreateUrl(){
        $productId = $this->getRequest()->getParam('product_id');
        return Mage::helper("adminhtml")->getUrl('*/deals/new', array('product_id' => $productId));
    }
}

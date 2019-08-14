<?php
  class PayRandom_PRPayment_Block_Adminhtml_Deals_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
  {
     public function __construct()
     {
          parent::__construct();
          $this->setId('deals_tabs');
          $this->setDestElementId('edit_form');
          $this->setTitle(Mage::helper('prpayment')->__('Deal'));
      }
      protected function _beforeToHtml()
      {
          $this->addTab('form_section', array(
                   'label' => Mage::helper('prpayment')->__('Deal discounts'),
                   'title' => Mage::helper('prpayment')->__('Deal discounts'),
                   'content' => $this->getLayout()
            ->createBlock('prpayment/adminhtml_deals_edit_tab_form')
            ->toHtml()
         ));
         return parent::_beforeToHtml();
    }
}
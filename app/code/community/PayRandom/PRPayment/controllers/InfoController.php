<?php
/**
 * @category    PayRandom
 * @package     PayRandom_PRPayment
 * @copyright   Copyright (c) 2013 PayRandom (http://www.payrandom.com)
 */

class PayRandom_PRPayment_InfoController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('prpayment_menu')
		->_addBreadcrumb(Mage::helper('prpayment')->__('PayRandom - Account info'), Mage::helper('prpayment')->__('PayRandom - Account info'));
		return $this;
	}
	public function indexAction() {
		$this->_initAction();
		$block = $this->getLayout()->createBlock('Mage_Core_Block_Template','prpayment',array('template' => 'prpayment/info.phtml'));

		$block->setVersion('Basic');
		$block->setExpirationDate();
		
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}

	public function currentDealsAction() {
		$this->loadLayout();
		$this->_setActiveMenu('prpayment_menu');
		$this->_addBreadcrumb('Pay Random Deal', 'Pay Random Deal');
		$this->_addContent($this->getLayout()->createBlock('prpayment/adminhtml_deals_current'));
		$this->renderLayout();
	}

	public function soldDealsAction() {
		$this->loadLayout();
		$this->_setActiveMenu('prpayment_menu');
		$this->_addBreadcrumb('Pay Random Deal', 'Pay Random Deal');
		$this->_addContent($this->getLayout()->createBlock('prpayment/adminhtml_deals_sold'));
		$this->renderLayout();
  }

	public function predefinedDealsAction() {
		$this->loadLayout();
		$this->_setActiveMenu('prpayment_menu');
		$this->_addBreadcrumb('Pay Random Deal', 'Pay Random Deal');
		$this->_addContent($this->getLayout()->createBlock('prpayment/adminhtml_deals_predefined'));
		$this->renderLayout();
	}

}

<?php
class PayRandom_PRPayment_Block_Form_Prpayment extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('prpayment/form/prpayment.phtml');
    }
}

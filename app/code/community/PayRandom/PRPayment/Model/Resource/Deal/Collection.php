<?php
 
class PayRandom_PRPayment_Model_Resource_Deal_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('prpayment/deal');
    }
}
<?php
class PayRandom_PRPayment_Block_Adminhtml_Deals_Sold_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
   public function __construct()
   {
       parent::__construct();
       $this->setId('productStatisticsGrid');
       $this->setDefaultSort('id');
       $this->setDefaultDir('ASC');
       $this->setSaveParametersInSession(true);
   }
   
   protected function _prepareCollection()
   {
      $collection = Mage::getModel('prpayment/productstatistics')->getCollection()
        ->addFieldToFilter('active', array('=' => 0));

      $this->setCollection($collection);
      return parent::_prepareCollection();
   }

   protected function _prepareColumns()
   {
       $this->addColumn('product_name',
               array(
                    'header' => Mage::helper('prpayment')->__('Nombre'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'name',
              ));
         $this->addColumn('deal_name',
               array(
                    'header' => Mage::helper('prpayment')->__('Ruleta'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'deal_name',
              ));
         $this->addColumn('min_price',
               array(
                    'header' => Mage::helper('prpayment')->__('Precio mínimo'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'min_price',
                    'renderer' => 'PayRandom_PRPayment_Block_Adminhtml_Renderer_Amount'
              ));
         $this->addColumn('max_price',
               array(
                    'header' => Mage::helper('prpayment')->__('Precio máximo'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'max_price',
                    'renderer' => 'PayRandom_PRPayment_Block_Adminhtml_Renderer_Amount'
              ));

         $this->addColumn('total_amount',
               array(
                    'header' => Mage::helper('prpayment')->__('Total en ventas'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'total_amount',
                    'renderer' => 'PayRandom_PRPayment_Block_Adminhtml_Renderer_Amount'
              ));
         $this->addColumn('units',
               array(
                    'header' => Mage::helper('prpayment')->__('Unidades'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'units',
              ));
         $this->addColumn('times_purchased',
               array(
                    'header' => Mage::helper('prpayment')->__('Transacciones'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'times_purchased',
              ));
         $this->addColumn('expected_average_price',
               array(
                    'header' => Mage::helper('prpayment')->__('Precio medio esperado'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'expected_average_price',
                    'renderer' => 'PayRandom_PRPayment_Block_Adminhtml_Renderer_Amount'
              ));
         
         $this->addColumn('average_price',
               array(
                    'header' => Mage::helper('prpayment')->__('Precio medio'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'average_price',
                    'renderer' => 'PayRandom_PRPayment_Block_Adminhtml_Renderer_Amount'
              ));
         $this->addColumn('start_date',
               array(
                    'header' => Mage::helper('prpayment')->__('Fecha inicio'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'start_date',
              ));
         $this->addColumn('end_date',
               array(
                    'header' => Mage::helper('prpayment')->__('Fecha final'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'end_date',
              ));

        return parent::_prepareColumns();
    }
    public function getRowUrl($row)
    {
        return false;
    }
}

<?php
class PayRandom_PRPayment_Block_Adminhtml_Deals_Predefined_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
   public function __construct()
   {
       parent::__construct();
       $this->setId('predefinedDealsGridInfo');
       $this->setDefaultSort('id');
       $this->setDefaultDir('ASC');
       $this->setSaveParametersInSession(true);
   }
   
   protected function _prepareCollection()
   {
      $collection = Mage::getModel('prpayment/predefineddeal')->getCollection();

      $this->setCollection($collection);
      return parent::_prepareCollection();
   }

   protected function _prepareColumns()
   {
      $avgPrice = 20;
      $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id')); 
      if($product->getPrice()){
        $avgPrice = $product->getPrice();
      }

      if($this->getRequest()->getParam('avg_price')){
        $avgPrice = $this->getRequest()->getParam('avg_price');
      }
      $this->setAvgPrice($avgPrice);
       $this->addColumn('id',
             array(
                    'align' =>'right',
                    'width' => '50px',
                    'index' => 'id',
                    'sortable' => false,
                    'filter' => false,
                    'renderer' => new PayRandom_PRPayment_Block_Adminhtml_Renderer_Option(),
               ));
        $this->addColumn('name',
               array(
                    'header' => Mage::helper('prpayment')->__('Nombre'),
                    'align' =>'left',
                    'filter' => false,
                    'index' => 'name',
              ));
        
        for($i = 0; $i < 8; $i++){
          $renderer = new PayRandom_PRPayment_Block_Adminhtml_Renderer_Discount($i);
          $renderer->setAvgPrice($avgPrice);
          $this->addColumn('discounts' . $i,
                 array(
                      'align' =>'right',
                      'index' => 'discounts',
                      'renderer' => $renderer,
                      'filter' => false,
                      'sortable' => false,
                ));
        }

        $renderer = new PayRandom_PRPayment_Block_Adminhtml_Renderer_Fixed_Value($avgPrice);
        $this->addColumn('priceavg',
               array(
                    'header' => Mage::helper('prpayment')->__('Precio medio'),
                    'align' =>'right',
                    'renderer' => $renderer,
                    'filter' => false,
                    'sortable' => false,
              ));

        $renderer = new PayRandom_PRPayment_Block_Adminhtml_Renderer_Balance($avgPrice);
        $this->addColumn('balance',
               array(
                    'header' => Mage::helper('prpayment')->__('Equilibrio'),
                    'align' =>'right',
                    'renderer' => $renderer,
                    'index' => 'discounts',
                    'filter' => false,
                    'sortable' => false,
              ));

        $renderer = new PayRandom_PRPayment_Block_Adminhtml_Renderer_Attraction($avgPrice);
        $this->addColumn('attraction',
               array(
                    'header' => Mage::helper('prpayment')->__('Atracción'),
                    'align' =>'left',
                    'renderer' => $renderer,
                    'index' => 'discounts',
                    'filter' => false,
                    'sortable' => false,
                    'width' => '100px',
              ));

         return parent::_prepareColumns();
    }
    public function getRowUrl($row)
    {
        return false;
    }
}

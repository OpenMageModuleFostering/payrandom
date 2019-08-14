  <?php
  class PayRandom_PRPayment_Block_Adminhtml_Deals_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
  {
    protected function _prepareForm()
    {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('deals_form',
        array('legend'=> Mage::helper('prpayment')->__('Roulette discounts')));

      $dealData = false;

      if ( Mage::registry('deals_data') && Mage::registry('deals_data')->getId() )
      {
        $dealData = Mage::registry('deals_data');
      }

      $fieldset->addField('name', 'text',
        array(
        'label' => Mage::helper('prpayment')->__('Name'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'name',
        'value' => ($dealData?$dealData->getName():''),
        )
      );

      for($i = 0; $i < 8; $i++){
        $fieldset->addField('discount' . $i, 'text',
          array(
          'label' => Mage::helper('prpayment')->__('Discount') . ' ' . ($i + 1),
          'class' => 'required-entry validate-percents',
          'required' => true,
          'name' => 'discount' . $i,
          'value' => ($dealData?$dealData->getDiscount($i):''),
          )
        );
      }

      $options = array(
        array(
            'value' => 'percentages',
            'label' => Mage::helper('prpayment')->__('Percentages')
        ),
        array(
            'value' => 'prices',
            'label' => Mage::helper('prpayment')->__('Prices')
        )
      );

      $fieldset->addField('deals_product_id', 'hidden', array(
        'class' => 'required-entry',
        'required' => true,
        'name' => 'deals_product_id',
        'value' => Mage::registry('deals_product_id')
        )
      );

      $fieldset2 = $form->addFieldset('deals_form2',
        array('legend'=> Mage::helper('prpayment')->__('Options')));

      $fieldset2->addField('view_format', 'select', array(
          'name' => 'view_format',
          'label' => Mage::helper('prpayment')->__('View format'),
          'title' => Mage::helper('prpayment')->__('View format'),
          'values' => $options,
          'value' => ($dealData?$dealData->getViewFormat():''),
      ));
      
      return parent::_prepareForm();
    }
  }
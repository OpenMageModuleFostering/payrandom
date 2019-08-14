<?php
	$installer = $this;
	$installer->startSetup();
	
	$table = $installer->getConnection()->newTable($installer->getTable('prpayment/deal'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Name')
    ->addColumn('kind', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Kind')
    ->addColumn('discounts', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    	'nullable' => false,
        ), 'Serialized discounts')
    ->addColumn('view_format', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'View format')
    ->addColumn('fixed_discount', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
        ), 'View format')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    	'nullable' => true,
        ), 'Created at')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    	'nullable' => true,
        ), 'Updated at');
	$installer->getConnection()->createTable($table);
	
	$table = $installer->getConnection()->newTable($installer->getTable('prpayment/dealproduct'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'ID')
    ->addColumn('deal_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        ), 'Deal ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        ), 'Product ID')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    	'nullable' => true,
        ), 'Created at')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    	'nullable' => true,
        ), 'Updated at');
	$installer->getConnection()->createTable($table);

    $table = $installer->getConnection()->newTable($installer->getTable('prpayment/predefineddeal'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Name')
    ->addColumn('discounts', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Serialized discounts')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        ), 'Created at')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        ), 'Updated at');
    $installer->getConnection()->createTable($table);

    $table = $installer->getConnection()->newTable($installer->getTable('prpayment/productstatistics'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Name')
    ->addColumn('deal_name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        ), 'Deal name')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        ), 'Product ID')
    ->addColumn('total_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Total amount')
    ->addColumn('times_purchased', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        ), 'Times purcharsed')
    ->addColumn('units', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        ), 'Units')
    ->addColumn('min_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Minimum price')
    ->addColumn('max_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Maximum price')
    ->addColumn('expected_average_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Expected average price')
    ->addColumn('average_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Average price')
    ->addColumn('start_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => true,
        ), 'Start date')
    ->addColumn('end_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => true,
        ), 'End date')
    ->addColumn('active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => true,
        ), 'Active')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        ), 'Created at')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        ), 'Updated at');
    $installer->getConnection()->createTable($table);
	
	$installer->endSetup();

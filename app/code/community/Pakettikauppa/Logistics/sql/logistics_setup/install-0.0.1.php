<?php
$installer = $this;
$installer->startSetup();
$order = $installer->getTable('sales_flat_order');
$installer->getConnection()
    ->addColumn($order, 'home_delivery_service_provider', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Home Delivery Provider'
        )
    );
$installer->getConnection()
    ->addColumn($order, 'pickup_point_provider', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Pickup Point Provider'
        )
    );
$installer->getConnection()
    ->addColumn($order, 'pickup_point_location', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Pickup Point Location'
        )
    );
$installer->getConnection()
    ->addColumn($order, 'pickup_point_zip', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Pickup Point Zip'
        )
    );
$installer->getConnection()
  ->addColumn($order, 'pickup_point_id', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point ID'
      )
  );
$installer->getConnection()
  ->addColumn($order, 'pickup_point_name', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point Name'
      )
  );
$installer->getConnection()
  ->addColumn($order, 'pickup_point_street_address', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point Street Address'
      )
  );
$installer->getConnection()
  ->addColumn($order, 'pickup_point_postcode', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point Postcode'
      )
  );
$installer->getConnection()
  ->addColumn($order, 'pickup_point_city', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point City'
      )
  );
$installer->getConnection()
  ->addColumn($order, 'pickup_point_country', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point Country'
      )
  );
$installer->getConnection()
    ->addColumn($order, 'pickup_point_description', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Pickup Point Description'
        )
    );
$installer->getConnection()
    ->addColumn($order, 'paketikauppa_smc', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Shipping Method Code'
        )
    );
$quote = $installer->getTable('sales_flat_quote');
$installer->getConnection()
    ->addColumn($quote, 'pickup_point_zip', array(
            'nullable' => true,
            'length' => 255,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'Pickup Point Zip'
        )
    );
$installer->endSetup();

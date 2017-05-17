<?php
$installer = $this;
$installer->startSetup();
$order = $installer->getTable('sales_flat_order');
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
$quote = $installer->getTable('sales_flat_quote');
$installer->getConnection()
  ->addColumn($quote, 'pickup_point_location', array(
          'nullable' => true,
          'length' => 255,
          'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
          'comment' => 'Pickup Point Location'
      )
  );
$installer->getConnection()
        ->addColumn($quote, 'pickup_point_zip', array(
                'nullable' => true,
                'length' => 255,
                'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
                'comment' => 'Pickup Point Zip'
            )
        );
$installer->endSetup();

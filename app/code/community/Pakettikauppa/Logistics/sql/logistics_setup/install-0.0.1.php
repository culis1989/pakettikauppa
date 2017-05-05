<?php
$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()
   ->newTable($installer->getTable('pakettikauppa_logistics'))
   ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
               array('identity'  => true,
                   'unsigned'  => true,
                   'nullable'  => false,
                   'primary'   => true
                   ), 'Id')
   ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
               array('nullable'  => false), 'Order Id')
   ->addColumn('provider', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Provider')
   ->addColumn('pickup_point_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
               array('nullable'  => true), 'Pickuppoint Id')
   ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Name')
   ->addColumn('street_address', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Street Address')
   ->addColumn('postcode', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Postcode')
   ->addColumn('city', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint City')
   ->addColumn('country', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Country')
   ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Description')
   ->addColumn('map_longitude', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Map Longitude')
   ->addColumn('map_latitude', Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
               array('nullable'  => true), 'Pickuppoint Map Latitude');

$installer->getConnection()->createTable($table);
$installer->endSetup();

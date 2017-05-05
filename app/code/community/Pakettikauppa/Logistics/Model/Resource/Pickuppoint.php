<?php
class Pakettikauppa_Logistics_Model_Resource_Pickuppoint extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('pakettikauppa_logistics/pickuppoint', 'id');
    }
}

<?php
class Pakettikauppa_Logistics_Block_Tracking extends Mage_Core_Block_Template
{

    function __construct() {
   }

   public function getTracking(){
     return $this->getRequest()->getParam('code');
   }

   public function getTrackingStatus(){
     return 'STATUS';
   }
}
?>

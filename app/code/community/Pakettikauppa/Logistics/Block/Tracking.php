<?php
class Pakettikauppa_Logistics_Block_Tracking extends Mage_Core_Block_Template
{
  protected $code;

  function __construct() {
    $this->code = $this->getRequest()->getParam('code');
  }

  public function getTracking(){
    return $this->code;
  }

  public function getTrackingStatus(){
    $tracking = Mage::helper('pakettikauppa_logistics/API')->getTracking($this->code);
    if(isset($tracking)){
      return array_reverse($tracking);
    }else{
      return false;
    }
  }
}
?>

<?php
class Pakettikauppa_Logistics_Model_Methods_Pickup
extends Mage_Shipping_Model_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface
{

  protected $_code = 'pktkp_pickuppoint';

  private function getZip(){
    return Mage::helper('pakettikauppa_logistics')->getZip();
  }

  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
  {
      /** @var Mage_Shipping_Model_Rate_Result $result */
      $result = Mage::getModel('shipping/rate_result');
      if($this->getZip()){
        $methods = Mage::helper('pakettikauppa_logistics/API')->getPickupPoints($this->getZip());
        if(count($methods)>0){
          foreach($methods as $method){
            $description = $method->name.' | '.$method->street_address.', '.$method->city.', '.$method->postcode;
            $name = $method->provider;
            $result->append($this->_getCustomRate($name,$description,$method->pickup_point_id, 999));
          }
        }
        return $result;
      }else{
        return $result;
      }
  }
  /**
   * Returns Allowed shipping methods
   *
   * @return array
   */
  public function getAllowedMethods()
  {
    if($this->getZip()){
      $methods = Mage::helper('pakettikauppa_logistics/API')->getPickupPoints($this->getZip());
      if(count($methods)>0){
        foreach($methods as $carrier){
          $array['shipping_'.$carrier->shipping_method_code] = $carrier->name;
        }
        return $array;
      }
    }
  }


  protected function _getCustomRate($name, $description, $method_code, $price)
  {
      $price = $this->getConfigData('price');
      /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
      $rate = Mage::getModel('shipping/rate_result_method');
      $rate->setCarrier($this->_code);
      $rate->setCarrierTitle($this->getConfigData('title'));
      $rate->setMethod($method_code);
      $rate->setMethodTitle($name);
      $rate->setMethodDescription($description);
      $rate->setPrice($price);
      $rate->setCost(0);
      return $rate;
  }

    public function isTrackingAvailable()
    {
        return true;
    }

    public function getTrackingInfo($tracking)
    {
      $data = explode(",", $tracking);
      $tracking = $data[0];
      $name = $data[1];
      $track = Mage::getModel('shipping/tracking_result_status');
      $base_url = Mage::getUrl('pakettikauppalogistics/shipment/index/');
      $track->setUrl($base_url.'code/'.$tracking)
              ->setTracking($tracking)
              ->setCarrierTitle($name);
      return $track;
    }
}

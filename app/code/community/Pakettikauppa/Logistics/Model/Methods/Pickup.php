<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');
class Pakettikauppa_Logistics_Model_Methods_Pickup
extends Mage_Shipping_Model_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface
{

  protected $_code = 'pakettikauppa_pickuppoint';

  private function getZip(){
    $zip = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getPostcode();
    if($zip != '-'){
      return $zip;
    }else{
      return false;
    }
  }

  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
  {
      /** @var Mage_Shipping_Model_Rate_Result $result */
      $result = Mage::getModel('shipping/rate_result');
      if($this->getZip()){
        $client = new Pakettikauppa\Client(array('test_mode' => true));
        $methods = json_decode($client->searchPickupPoints($this->getZip()));
        foreach($methods as $method){
          $description = $method->name.' | '.$method->street_address.', '.$method->city.', '.$method->postcode;
          $name = $method->provider;
          $result->append($this->_getCustomRate($name,$description,$method->pickup_point_id, 999));
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
      $client = new Pakettikauppa\Client(array('test_mode' => true));
      $methods = json_decode($client->searchPickupPoints($this->getZip()));
      foreach($methods as $carrier){
        $array['shipping_'.$carrier->shipping_method_code] = $carrier->name;
      }
      return $array;
    }else{
      return false;
    }
  }


  protected function _getCustomRate($name, $description, $method_code, $price)
  {
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
}

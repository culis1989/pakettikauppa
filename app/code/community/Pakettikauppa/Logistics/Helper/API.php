<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment/Sender.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment/AdditionalService.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment/Info.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment/Parcel.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');

use Pakettikauppa\Shipment;
use Pakettikauppa\Shipment\Sender;
use Pakettikauppa\Shipment\Receiver;
use Pakettikauppa\Shipment\AdditionalService;
use Pakettikauppa\Shipment\Info;
use Pakettikauppa\Shipment\Parcel;

use Pakettikauppa\Client;


class Pakettikauppa_Logistics_Helper_API extends Mage_Core_Helper_Abstract
{
  protected $test_mode;

  function __construct(){
    $this->test_mode = true;
  }

  public function writeQuoteToOrder($quote, $order){

    $pickup_point_location = $quote->getData('pickup_point_location');
    $pickup_point_zip = $quote->getData('pickup_point_zip');

    $home_delivery_service_provider = $quote->getData('home_delivery_service_provider');
    $pickup_point_provider = $quote->getData('pickup_point_provider');
    $pickup_point_id = $quote->getData('pickup_point_id');
    $pickup_point_name = $quote->getData('pickup_point_name');
    $pickup_point_street_address = $quote->getData('pickup_point_street_address');
    $pickup_point_postcode = $quote->getData('pickup_point_postcode');
    $pickup_point_city = $quote->getData('pickup_point_city');
    $pickup_point_country = $quote->getData('pickup_point_country');
    $pickup_point_description = $quote->getData('pickup_point_description');

    if(isset($pickup_point_zip)){
      $zip = $pickup_point_zip;
    }else{
      $zip = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getPostcode();
    }

    $order->setData('pickup_point_location', $pickup_point_location);
    $order->setData('pickup_point_zip', $zip);
    $order->setData('pickup_point_provider', $pickup_point_provider);
    $order->setData('pickup_point_id', $pickup_point_id);
    $order->setData('pickup_point_name', $pickup_point_name);
    $order->setData('pickup_point_street_address', $pickup_point_street_address);
    $order->setData('pickup_point_postcode', $pickup_point_postcode);
    $order->setData('pickup_point_city', $pickup_point_city);
    $order->setData('pickup_point_country', $pickup_point_country);
    $order->setData('pickup_point_description', $pickup_point_description);
    $order->setData('home_delivery_service_provider',$home_delivery_service_provider);
  }

  public function unsetPakettikauppaData($checkout){
    $checkout->unsetData('pickup_point_provider');
    $checkout->unsetData('pickup_point_id');
    $checkout->unsetData('pickup_point_name');
    $checkout->unsetData('pickup_point_street_address');
    $checkout->unsetData('pickup_point_postcode');
    $checkout->unsetData('pickup_point_city');
    $checkout->unsetData('pickup_point_country');
    $checkout->unsetData('pickup_point_description');
    $checkout->unsetData('home_delivery_service_provider');
  }

  public function getTracking($code){
    $client = new Client(array('test_mode' => $this->test_mode));
    $tracking = $client->getShipmentStatus($code);
    return json_decode($tracking);
  }

  public function getHomeDelivery(){
    $client = new Client(array('test_mode' => $this->test_mode));
    $result = json_decode($client->listShippingMethods());
    return $result;
  }

  public function getPickupPoints($zip){
    $client = new Client(array('test_mode' => $this->test_mode));
    $result = json_decode($client->searchPickupPoints($zip));
    return $result;
  }

}
?>

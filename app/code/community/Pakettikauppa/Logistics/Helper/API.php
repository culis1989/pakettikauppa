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

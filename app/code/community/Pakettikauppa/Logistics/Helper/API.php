<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment/Sender.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Shipment/Receiver.php');
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

  protected $client;

  function __construct(){
    $this->client = new Client(array('test_mode' => true));
  }

  public function getTracking($code){
    $client = $this->client;
    $tracking = $client->getShipmentStatus($code);
    return json_decode($tracking);
  }

  public function getHomeDelivery(){
    $client = $this->client;
    $result = json_decode($client->listShippingMethods());
    return $result;
  }

  public function getPickupPoints($zip){
    $client = $this->client;
    $result = json_decode($client->searchPickupPoints($zip));
    return $result;
  }

  public function createShipment($order){

    $sender = new Sender();

    // CHANGE TO REAL SHOP DETAILS
    $sender->setName1('RT MODULE DEVELOPMENT');
    $sender->setAddr1('Development Address');
    $sender->setPostcode('11080');
    $sender->setCity('PIXEL2GO');
    $sender->setCountry('RS');
    // CHANGE TO REAL SHOP DETAILS

    $shipping_data = $order->getShippingAddress();
    $firstname = $shipping_data->getData('firstname');
    $middlename = $shipping_data->getData('middlename');
    $lastname = $shipping_data->getData('lastname');

    $name = $firstname.' '.$middlename.' '.$lastname;


    // CHANGE RECEIVER IF PICKUP POINT
    $receiver = new Receiver();
    $receiver->setName1($name);
    $receiver->setAddr1($shipping_data->getData('street'));
    $receiver->setPostcode($shipping_data->getData('postcode'));
    $receiver->setCity($shipping_data->getData('city'));
    $receiver->setCountry($shipping_data->getData('country_id'));
    $receiver->setEmail($shipping_data->getData('email'));
    $receiver->setPhone($shipping_data->getData('telephone'));

    $info = new Info();
    $info->setReference($order->getIncrementId());

    $additional_service = new AdditionalService();
    // $additional_service->setServiceCode(3104); // fragile

    $parcel = new Parcel();
    $parcel->setReference($order->getIncrementId());
    $parcel->setWeight(0.5); // kg

    // GET VOLUME
    $parcel->setVolume(0.001); // m3
    //$parcel->setContents('Stuff and thingies');

    $shipping = $order->getShippingMethod();
    $shipping_code = substr($shipping, strrpos($shipping, '_') + 1);

    $shipment = new Shipment();
    $shipment->setShippingMethod(2103); // shipping_method_code that you can get by using listShippingMethods()
    $shipment->setSender($sender);
    $shipment->setReceiver($receiver);
    $shipment->setShipmentInfo($info);
    $shipment->addParcel($parcel);
    $shipment->addAdditionalService($additional_service);

    $client = $this->client;

    try {
        if ($client->createTrackingCode($shipment)) {
            if($client->fetchShippingLabel($shipment)){
              $dir = Mage::getBaseDir() . "/labels";
              if (!is_dir($dir)) {
                mkdir($dir);
              }
              file_put_contents($dir.'/'.$shipment->getTrackingCode() . '.pdf', base64_decode($shipment->getPdf()));
              return (string)$shipment->getTrackingCode();
            }

        }
    } catch (\Exception $ex)  {
         echo $ex->getMessage();
    }
  }

}
?>

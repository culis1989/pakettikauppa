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
  protected $key;
  protected $secret;
  protected $development;

  function __construct(){
    $this->development = Mage::getStoreConfig('pakettikauppa/api/development');
    if($this->development == 1){
      $this->client = new Client(array('test_mode' => true));
    }else{
      $this->key = Mage::getStoreConfig('pakettikauppa/api/key');
      $this->secret = Mage::getStoreConfig('pakettikauppa/api/secret');
      if(isset($this->key) && isset($this->secret)){
        $params['api_key'] = $this->key;
        $params['secret'] = $this->secret;
        $this->client = new Client($params);
      }else{
        Mage::throwException('Please insert API and secret key.');
      }
    }
  }

  public function getTracking($code){
    $client = $this->client;
    $tracking = $client->getShipmentStatus($code);
    return json_decode($tracking);
  }



  public function getHomeDelivery($all = false){

    $client = $this->client;
    $result = [];
    $methods = json_decode($client->listShippingMethods());
    if($all == true){
      return $methods;
    }else{
      $counter = 0;
      foreach($methods as $method){
        if(count($method->additional_services)>0){
          foreach($method->additional_services as $service){
            if($service->service_code == '2106'){
              $method->name = null;
              $method->shipping_method_code = null;
              $method->description = null;
              $method->service_provider = null;
              $method->additional_services = null;
            }
          }
        }
      }
      foreach($methods as $method){
        if($method->name != null){
          $result[] = $method;
        }
      }
      return $result;
    }
  }

  public function getPickupPoints($zip){
    $client = $this->client;
    $result = json_decode($client->searchPickupPoints($zip));
    return $result;
  }

  public function createShipment($order){

    $sender = new Sender();

    $store = $order->getStoreId();
    $_sender_name = Mage::getStoreConfig('pakettikauppa/sender/name',$store);
    $_sender_address = Mage::getStoreConfig('pakettikauppa/sender/address',$store);
    $_sender_city = Mage::getStoreConfig('pakettikauppa/sender/city',$store);
    $_sender_postcode = Mage::getStoreConfig('pakettikauppa/sender/postcode',$store);
    $_sender_country = Mage::getStoreConfig('pakettikauppa/sender/country',$store);

    $sender->setName1($_sender_name);
    $sender->setAddr1($_sender_address);
    $sender->setPostcode($_sender_postcode);
    $sender->setCity($_sender_city);
    $sender->setCountry($_sender_country);


    $shipping_data = $order->getShippingAddress();
    $firstname = $shipping_data->getData('firstname');
    $middlename = $shipping_data->getData('middlename');
    $lastname = $shipping_data->getData('lastname');

    $name = $firstname.' '.$middlename.' '.$lastname;

    if(strpos($order->getShippingMethod(), 'pktkp_pickuppoint') !== false) {
      $shop = $order->getData('pickup_point_name');
      $name = $shop.' ('.$firstname.' '.$middlename.' '.$lastname.')';
      $_receiver_address = $order->getData('pickup_point_street_address');
      $_receiver_postcode = $order->getData('pickup_point_postcode');
      $_receiver_city = $order->getData('pickup_point_city');
      $_receiver_country = $order->getData('pickup_point_country');
    }else{
      $name = $firstname.' '.$middlename.' '.$lastname;
      $_receiver_address = $shipping_data->getData('street');
      $_receiver_postcode = $shipping_data->getData('postcode');
      $_receiver_city = $shipping_data->getData('city');
      $_receiver_country = $shipping_data->getData('country_id');
    }

    $receiver = new Receiver();
    $receiver->setName1($name);
    $receiver->setAddr1($_receiver_address);
    $receiver->setPostcode($_receiver_postcode);
    $receiver->setCity($_receiver_city);
    $receiver->setCountry($_receiver_country);
    $receiver->setEmail($shipping_data->getData('email'));
    $receiver->setPhone($shipping_data->getData('telephone'));

    $info = new Info();
    $info->setReference($order->getIncrementId());

    $additional_service = new AdditionalService();
    // $additional_service->setServiceCode(3104); // fragile

    $parcel = new Parcel();
    $parcel->setReference($order->getIncrementId());
    $parcel->setWeight($order->getData('weight')); // kg

    // GET VOLUME
    $parcel->setVolume(0.001); // m3
    //$parcel->setContents('Stuff and thingies');

    $shipment = new Shipment();
    $shipment->setShippingMethod($order->getData('paketikauppa_smc')); // shipping_method_code that you can get by using listShippingMethods()
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
        Mage::throwException('Shipment not created, please double check your store settings on STORE view level. Additional message: '.$ex->getMessage());
    }
  }

}
?>

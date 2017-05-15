<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');
class Pakettikauppa_Logistics_PickuppointController extends Mage_Core_Controller_Front_Action{

  public function indexAction(){
    // $pickuppoints = Mage::getModel('pakettikauppa_logistics/pickuppoint')->getCollection()->getData();
    // var_dump($pickuppoints);
    // $client = new Pakettikauppa\Client(array('test_mode' => true));
    // $methods = json_decode($client->searchPickupPoints('90940'));
    // var_dump($methods);

    $_order = Mage::getModel('sales/order')->load('100000014', 'increment_id');
    var_dump($_order->getData());
  }
}
?>

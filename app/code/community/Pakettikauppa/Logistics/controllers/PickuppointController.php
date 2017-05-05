<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');
class Pakettikauppa_Logistics_PickuppointController extends Mage_Core_Controller_Front_Action{

  public function indexAction(){
    $pickuppoints = Mage::getModel('pakettikauppa_logistics/pickuppoint')->getCollection()->getData();
    var_dump($pickuppoints);
  }
}
?>

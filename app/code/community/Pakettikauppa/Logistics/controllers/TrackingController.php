<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');
class Pakettikauppa_Logistics_TrackingController extends Mage_Core_Controller_Front_Action{

  public function indexAction(){
    echo 'Hello tracking '.$_GET['t'].'!';
  }
}
?>

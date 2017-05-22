<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');
class Pakettikauppa_Logistics_ShipmentController extends Mage_Core_Controller_Front_Action{

  public function indexAction(){
    $this->loadLayout();
    $this->getLayout()->getBlock('tracking')->assign('code', $this->getRequest()->getParam('code'));
    $this->renderLayout();

  }

}
?>

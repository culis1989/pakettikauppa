<?php
class Pakettikauppa_Logistics_Model_Observer {
  public function salesOrderShipmentSaveBefore($observer){
    $shipment = $observer->getEvent()->getShipment();
    if(count($shipment->getAllTracks())==0){
      $track = Mage::getModel('sales/order_shipment_track')
                      ->setCarrierCode('pakettikauppa_homedelivery')
                      ->setTitle('Posti2')
                      ->setNumber('824343454454');
      $shipment->addTrack($track);
    }
   }
 }

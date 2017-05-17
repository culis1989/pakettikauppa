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

   public function salesOrderSaveBefore($observer){
      $quote = $observer->getEvent()->getData('quote');
      $pickup_point_location = Mage::getSingleton('checkout/session')->getQuote()->getData('pickup_point_location');
      $pickup_point_zip = Mage::getSingleton('checkout/session')->getQuote()->getData('pickup_point_zip');

      $order = $observer->getEvent()->getData('order');
      $order->setData('pickup_point_location', $pickup_point_location);
      $order->setData('pickup_point_zip', $pickup_point_zip);
   }
 }

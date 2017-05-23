<?php
class Pakettikauppa_Logistics_Model_Observer {
  public function salesOrderShipmentSaveBefore($observer){
    $shipment = $observer->getEvent()->getShipment();
    $shipping_method = $shipment->getOrder()->getData('shipping_method');
    if(Mage::helper('pakettikauppa_logistics')->isPakettikauppa($shipping_method)){
      if(count($shipment->getAllTracks())==0){

        $code = Mage::helper('pakettikauppa_logistics')->getMethod($shipping_method);

        if(Mage::helper('pakettikauppa_logistics')->getMethod($shipping_method)=='pakettikauppa_homedelivery'){
          $carrier = $shipment->getOrder()->getData('home_delivery_service_provider');
        }
        if(Mage::helper('pakettikauppa_logistics')->getMethod($shipping_method)=='pakettikauppa_pickuppoint'){
          $carrier = $shipment->getOrder()->getData('pickup_point_provider');
        }

        $orderId = $shipment->getOrder()->getID();
        $order = Mage::getModel('sales/order')->load($orderId);

        $tracking_number = Mage::helper('pakettikauppa_logistics/API')->createShipment($order);

        // GET TRACKING NUMBER HERE
        $track = Mage::getModel('sales/order_shipment_track')
                        ->setCarrierCode($code)
                        ->setTitle('Home Delivery')
                        ->setNumber($tracking_number.','.$carrier);
        $shipment->addTrack($track);
      }
    }
   }

   public function salesOrderSaveBefore($observer){

     $quote = Mage::getSingleton('checkout/session')->getQuote();
     $shipping_method_code = $quote->getShippingAddress()->getShippingMethod();

     if(Mage::helper('pakettikauppa_logistics')->isPakettikauppa($shipping_method_code)){
       $order = $observer->getEvent()->getData('order');
       Mage::helper('pakettikauppa_logistics/API')->writeQuoteToOrder($quote, $order);
      }
   }
 }

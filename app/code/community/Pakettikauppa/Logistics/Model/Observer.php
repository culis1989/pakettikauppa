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

        // GET TRACKING NUMBER HERE
        $trcking_number = '824343454454';
        $track = Mage::getModel('sales/order_shipment_track')
                        ->setCarrierCode($code)
                        ->setTitle('Home Delivery')
                        ->setNumber($trcking_number.','.$carrier);
        $shipment->addTrack($track);
      }
    }
   }

   public function salesOrderSaveBefore($observer){

     $quote_session = Mage::getSingleton('checkout/session')->getQuote();
     $shipping_method_code = $quote_session->getShippingAddress()->getShippingMethod();

     if(Mage::helper('pakettikauppa_logistics')->isPakettikauppa($shipping_method_code)){

        $quote = $observer->getEvent()->getData('quote');

        // OLD DATA
        $pickup_point_location = $quote_session->getData('pickup_point_location');
        $pickup_point_zip = $quote_session->getData('pickup_point_zip');

        // NEW DATA
        $home_delivery_service_provider = $quote_session->getData('home_delivery_service_provider');
        $pickup_point_provider = $quote_session->getData('pickup_point_provider');
        $pickup_point_id = $quote_session->getData('pickup_point_id');
        $pickup_point_name = $quote_session->getData('pickup_point_name');
        $pickup_point_street_address = $quote_session->getData('pickup_point_street_address');
        $pickup_point_postcode = $quote_session->getData('pickup_point_postcode');
        $pickup_point_city = $quote_session->getData('pickup_point_city');
        $pickup_point_country = $quote_session->getData('pickup_point_country');
        $pickup_point_description = $quote_session->getData('pickup_point_description');

        if(isset($pickup_point_zip)){
          $zip = $pickup_point_zip;
        }else{
          $zip = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getPostcode();
        }

        $order = $observer->getEvent()->getData('order');
        $order->setData('pickup_point_location', $pickup_point_location);
        $order->setData('pickup_point_zip', $zip);
        $order->setData('pickup_point_provider', $pickup_point_provider);
        $order->setData('pickup_point_id', $pickup_point_id);
        $order->setData('pickup_point_name', $pickup_point_name);
        $order->setData('pickup_point_street_address', $pickup_point_street_address);
        $order->setData('pickup_point_postcode', $pickup_point_postcode);
        $order->setData('pickup_point_city', $pickup_point_city);
        $order->setData('pickup_point_country', $pickup_point_country);
        $order->setData('pickup_point_description', $pickup_point_description);
        $order->setData('home_delivery_service_provider',$home_delivery_service_provider);
      }
   }
 }

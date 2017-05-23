<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Pakettikauppa_Logistics_Checkout_OnepageController
extends Mage_Checkout_OnepageController{
  public function savePickuppointZipAction(){
    $code = $_GET['code'];
    $quote = Mage::getSingleton('checkout/cart')->getQuote();
    $pickup_point_zip = $quote->getData('pickup_point_zip');
    if($pickup_point_zip == ''){
      $zip = $quote->getShippingAddress()->getPostcode();
    }else{
      $zip = $pickup_point_zip;
    }
    $checkout = Mage::getSingleton('checkout/session')->getQuote();
    $methods = $checkout->getShippingAddress()->getShippingRatesCollection()->getData();
    $is_pickup_point = false;
     foreach($methods as $method){
       if($method['carrier'] == 'pakettikauppa_pickuppoint' && $method['code'] == $code ){
         $pickup_methods = Mage::helper('pakettikauppa_logistics/API')->getPickupPoints($zip);
         foreach($pickup_methods as $pickup_method){
           if('pakettikauppa_pickuppoint_'.$pickup_method->pickup_point_id == $code){
              Mage::helper('pakettikauppa_logistics/API')->unsetPakettikauppaData($checkout);
              $checkout->setData('pickup_point_provider', $pickup_method->provider);
              $checkout->setData('pickup_point_id', $pickup_method->pickup_point_id);
              $checkout->setData('pickup_point_name', $pickup_method->name);
              $checkout->setData('pickup_point_street_address', $pickup_method->street_address);
              $checkout->setData('pickup_point_postcode', $pickup_method->postcode);
              $checkout->setData('pickup_point_city', $pickup_method->city);
              $checkout->setData('pickup_point_country', $pickup_method->country);
              $checkout->setData('pickup_point_description', $pickup_method->description);
              $checkout->save();
              $is_pickup_point = true;
           }
         }
       }

       if($method['carrier'] == 'pakettikauppa_homedelivery' && $method['code'] == $code ){
         $homedelivery_methods = Mage::helper('pakettikauppa_logistics/API')->getHomeDelivery();
         foreach($homedelivery_methods as $homedelivery_method){
           if('pakettikauppa_homedelivery_'.$homedelivery_method->shipping_method_code == $code){
             Mage::helper('pakettikauppa_logistics/API')->unsetPakettikauppaData($checkout);
             $checkout->setData('home_delivery_service_provider', $homedelivery_method->service_provider);
             $checkout->save();
             $is_pickup_point = true;
           }
         }
       }
     }

     if(!$is_pickup_point){
       Mage::helper('pakettikauppa_logistics/API')->unsetPakettikauppaData($checkout);
       $checkout->save();
     }

  }
  public function reloadShippingMethodsAction(){

    $cart = Mage::getSingleton('checkout/cart');
    $zipcode = $_GET['zip'];
    $zip_shipping = $cart->getQuote()->getShippingAddress()->getPostcode();
    $country_shipping = $cart->getQuote()->getShippingAddress()->getCountryId();

    $quote = $cart->getQuote();
    $quote->setData('pickup_point_zip',$zipcode);
    $address = $quote->getShippingAddress();
    $address->setCountryId($country_shipping)
            ->setPostcode($zip_shipping)
            ->setCollectShippingrates(true);
    $cart->save();

    $result['update_section'] = array(
            'name' => 'shipping-method',
            'html' => $this->_getShippingMethodsHtml()
    );

    return $this->_prepareDataJSON($result);

  }
}

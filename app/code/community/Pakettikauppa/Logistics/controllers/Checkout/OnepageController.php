<?php
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/autoload.php');
require_once(Mage::getBaseDir('lib') . '/pakettikauppa/Client.php');
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Pakettikauppa_Logistics_Checkout_OnepageController
extends Mage_Checkout_OnepageController{
  public function savePickuppointZipAction(){
    $client = new Pakettikauppa\Client(array('test_mode' => true));
    $code = $_GET['code'];
    $zip = $_GET['zip'];
    $checkout = Mage::getSingleton('checkout/session')->getQuote();
    $methods = $checkout->getShippingAddress()->getShippingRatesCollection()->getData();
    $is_pickup_point = false;
     foreach($methods as $method){
       if($method['carrier'] == 'pakettikauppa_pickuppoint' && $method['code'] == $code ){
         $pickup_methods = json_decode($client->searchPickupPoints($zip));
         foreach($pickup_methods as $pickup_method){
           if('pakettikauppa_pickuppoint_'.$pickup_method->pickup_point_id == $code){
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
     }


     if(!$is_pickup_point){
       $checkout->unsetData('pickup_point_provider');
       $checkout->unsetData('pickup_point_id');
       $checkout->unsetData('pickup_point_name');
       $checkout->unsetData('pickup_point_street_address');
       $checkout->unsetData('pickup_point_postcode');
       $checkout->unsetData('pickup_point_city');
       $checkout->unsetData('pickup_point_country');
       $checkout->unsetData('pickup_point_description');
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

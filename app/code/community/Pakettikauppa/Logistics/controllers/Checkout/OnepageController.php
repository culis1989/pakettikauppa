<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Pakettikauppa_Logistics_Checkout_OnepageController
extends Mage_Checkout_OnepageController{
  
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

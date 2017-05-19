<?php
class Pakettikauppa_Logistics_Helper_Data extends Mage_Core_Helper_Abstract
{
  public function getZip(){
    $zip_pickup = Mage::getSingleton('checkout/cart')->getQuote()->getData('pickup_point_zip');
    $zip_shipping = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getPostcode();
     if(isset($zip_pickup)){
       return $zip_pickup;
    }elseif($zip_shipping != '-'){
      return $zip_shipping;
    }else{
      return false;
    }
  }

  public function getMethodImage($code,$provider){
    //if (strpos($code, 'pakettikauppa_pickuppoint') !== false || strpos($code, 'pakettikauppa_homedelivery') !== false) {
    if (strpos($code, 'pakettikauppa_pickuppoint') !== false) {
      $latin_provider = iconv('UTF-8', 'ASCII//TRANSLIT', $provider);
      $icon = strtolower(str_replace(' ', '_', $latin_provider));
      return '<img class="shipping_provider_logo_logistics" src="/media/pakettikauppa/providers/'.$icon.'.png" alt="'.$provider.'"/>';
    }else{
      return false;
    }
  }

}

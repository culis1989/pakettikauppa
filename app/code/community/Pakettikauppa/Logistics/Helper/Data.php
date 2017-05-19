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
      if (strpos($code, 'pakettikauppa_pickuppoint') !== false || strpos($code, 'pakettikauppa_homedelivery') !== false) {
        $latin_provider = iconv('UTF-8', 'ASCII//TRANSLIT', $provider);
        $icon = strtolower(str_replace(' ', '_', $latin_provider));
        return '<img class="shipping_provider_logo_logistics" src="/media/pakettikauppa/providers/'.$icon.'.png" alt="'.$provider.'"/>';
      }else{
        return false;
      }
    }

    public function isPakettikauppa($code){
      if(strpos($code, 'pakettikauppa_pickuppoint') !== false || strpos($code, 'pakettikauppa_homedelivery') !== false) {
        return true;
      }else{
        return false;
      }
    }

    public function getMethod($code){
      if(strpos($code, 'pakettikauppa_pickuppoint') !== false) {
        return 'pakettikauppa_pickuppoint';
      }
      if(strpos($code, 'pakettikauppa_homedelivery') !== false) {
        return 'pakettikauppa_homedelivery';
      }
    }

    public function getCode($code){
      if(strpos($code, 'pakettikauppa_pickuppoint') !== false || strpos($code, 'pakettikauppa_homedelivery') !== false) {
        $shipping_code = str_replace('pakettikauppa_pickuppoint', '', $code);
        $shipping_code = str_replace('pakettikauppa_homedelivery', '', $shipping_code);
        return $shipping_code;
      }
    }
    
  }

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
}

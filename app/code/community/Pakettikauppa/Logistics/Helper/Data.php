<?php
class Pakettikauppa_Logistics_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getShipmentStatusText($code){
      switch ($code) {
          case "13":
              $status = "Item is collected from sender - picked up";
              break;
          case "20":
              $status = "Exception";
              break;
          case "22":
              $status = "Item has been handed over to the recipient";
              break;
          case "31":
              $status = "Item is in transport";
              break;
          case "38":
              $status = "C.O.D payment is paid to the sender";
              break;
          case "45":
              $status = "Informed consignee of arrival";
              break;
          case "48":
              $status = "Item is loaded onto a means of transport";
              break;
          case "56":
              $status = "Item not delivered – delivery attempt made";
              break;
          case "68":
              $status = "Pre-information is received from sender";
              break;
          case "71":
              $status = "Item is ready for delivery transportation";
              break;
          case "77":
              $status = "Item is returning to the sender";
              break;
          case "91":
              $status = "Item is arrived to a post office";
              break;
          case "99":
              $status = "Outbound";
              break;
          default:
              $status = "Unknown";
      }
      return $status;
    }
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
      if (strpos($code, 'pktkp_pickuppoint') !== false || strpos($code, 'pktkp_homedelivery') !== false) {
        $latin_provider = iconv('UTF-8', 'ASCII//TRANSLIT', $provider);
        $icon = strtolower(str_replace(' ', '_', $latin_provider));
        return '<img class="shipping_provider_logo_logistics" src="/media/pakettikauppa/providers/'.$icon.'.png" alt="'.$provider.'"/>';
      }else{
        return false;
      }
    }

    public function isPakettikauppa($code){
      if(strpos($code, 'pktkp_pickuppoint') !== false || strpos($code, 'pktkp_homedelivery') !== false) {
        return true;
      }else{
        return false;
      }
    }

    public function getMethod($code){
      if(strpos($code, 'pktkp_pickuppoint') !== false) {
        return 'pktkp_pickuppoint';
      }
      if(strpos($code, 'pktkp_homedelivery') !== false) {
        return 'pktkp_homedelivery';
      }
    }

    public function getCode($code){
      if(strpos($code, 'pktkp_pickuppoint') !== false || strpos($code, 'pktkp_homedelivery') !== false) {
        $shipping_code = str_replace('pktkp_pickuppoint', '', $code);
        $shipping_code = str_replace('pktkp_homedelivery', '', $shipping_code);
        return $shipping_code;
      }
    }

  }

<?php
class Pakettikauppa_Logistics_Model_Rate_Result extends Mage_Shipping_Model_Rate_Result{

  // OVERRIDE IN ORDER TO DISPLAY PICKUPPOINTS
  // IN CORRECT ORDER ACCORDING TO DISTANCE
  public function sortRatesByPrice(){
    return $this;
  }
}

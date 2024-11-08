<?php
 namespace App\Classes\Payments\ZaloPay;
class ZaloPayMacGenerator
{
  static function compute(string $params, string $key = null)
  {
    if (is_null($key)) {
      $key = env('KEY_1_ZALO');
    }
    return hash_hmac("sha256", $params, $key);
  }

  private static function createOrderMacData(Array $order)
  {
    return $order["appid"]."|".$order["apptransid"]."|".$order["appuser"]."|".$order["amount"]
      ."|".$order["apptime"]."|".$order["embeddata"]."|".$order["item"];
  }

  static function createOrder(Array $order)
  {
    return self::compute(self::createOrderMacData($order));
  }

  static function quickPay(Array $order, String $paymentcodeRaw)
  {
    return self::compute(self::createOrderMacData($order)."|".$paymentcodeRaw);
  }

  static function refund(Array $params)
  {
    return self::compute($params['appid']."|".$params['zptransid']."|".$params['amount']."|".$params['description']."|".$params['timestamp']);
  }

  static function getOrderStatus(Array $params)
  {
    return self::compute($params['appid']."|".$params['apptransid']."|".env('KEY_1_ZALO'));
  }

  static function getRefundStatus(Array $params)
  {
    return self::compute($params['appid']."|".$params['mrefundid']."|".$params['timestamp']);
  }

  static function getBankList(Array $params)
  {
    return self::compute($params['appid']."|".$params['reqtime']);
  }

  static function redirect(Array $params , string $key2)
  {
    return self::compute($params['appid']."|".$params['apptransid']."|".$params['pmcid']."|".$params['bankcode']
      ."|".$params['amount']."|".$params['discountamount']."|".$params["status"], $key2);
  }
}
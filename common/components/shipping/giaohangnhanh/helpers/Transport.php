<?php

namespace frontend\components;
use common\components\shipping\ClaShipping;
use Yii;

/**
 * Xử lý giỏ hàng
 */
class Transport {

    const PARAM_CART = 'transport';

    public $transports = [];

    public function __construct() {
        $this->transports = Yii::$app->session[self::PARAM_CART];
    }

    public function add($shop_id, $data = []) {
        $key = $shop_id;
        //
        $this->transports[$key] = $data;
        //
        Yii::$app->session[self::PARAM_CART] = $this->transports;
    }

    public function remove($key) {
        unset($this->transports[$key]);
        Yii::$app->session[self::PARAM_CART] = $this->transports;
    }

    public function removeAll() {
        $this->transports = [];
        Yii::$app->session[self::PARAM_CART] = $this->transports;
    }

    public function addTransport($shop_id, $method, $options, $data) {
        $tran = new Transport();
        $transport['method'] = $method;
        $transport['order'] = array(
                "pick_name" => "HCM-nội thành",
                "pick_address" => $options['pick_address'],
                "pick_province" => $options['pick_province'],
                "pick_district" => $options['pick_district'],
                "pick_tel" => "0911222333",
                "tel" => "0911222333",
                "name" => "GHTK - HCM - Noi Thanh",
                "address" => $options['address'],
                "province" => $options['province'],
                "district" => $options['district'],
                "is_freeship" => "1",
                "pick_date" => date('Y-m-d', time()),
                "pick_money" => $data['fee'],
                "note" => "",
                "value" => 0
            );
        $tran->add($shop_id, $transport);
    }

    public function addTransportGhn($shop_id, $method, $options, $data) {
        $tran = new Transport();
        $transport['method'] = $method;
        $transport['order'] = array(
                "pick_money" => 0,
                "ServiceID" => $options['ServiceID'],
                "Weight" => $options['Weight'],
                "Width" => $options['Width'],
                "Length" => $options['Length'],
                "Height" => $options['Height'],
                "FromDistrictID" => $options['FromDistrictID'],
                "ToDistrictID" => $options['ToDistrictID'],
            );
        $tran->add($shop_id, $transport);
    }

    public static function getCost($shop_id, $method, $options) {
        $tran = new Transport();
        if($method) {
            switch ($method) {
                case ClaShipping::METHOD_GHTK :
                    $claShipping = new ClaShipping();
                    $claShipping->loadVendor(['method' => $method]);
                    $data = $claShipping->calculateFee($options);
                    $tran->addTransport($shop_id, $method, $options['data'], $data);
                    return isset($data['fee']) ? $data['fee'] : false;
                
                case ClaShipping::METHOD_GHN :
                    $claShipping = new ClaShipping();
                    $claShipping->loadVendor(['method' => $method]);
                    $data = $claShipping->calculateFee($options);
                    $options['data']['ServiceID'] = (new \common\components\shipping\giaohangnhanh\helpers\Request())->getService($options['data']);
                    $tran->addTransportGhn($shop_id, $method, $options['data'], $data);
                    return isset($data['fee']) ? $data['fee'] : false;
            }
        }
        $tran->add($shop_id, ['method' => 0]);
        return false;
    }

    public static function getInfoTransport($orderShop, $shop, $address) {
        if($orderShop->transport_type) {
            $claShipping = new ClaShipping();
            $claShipping->loadVendor(['method' => $orderShop->transport_type]);
            $options['data'] = $claShipping->vendor->getDataCreateOrder($orderShop, $shop, $address);
            $data = $claShipping->createOrder($options);
            if(isset($data['success']) && isset($data['success'])) {
                return $data;
            } else {
                return 0;
            }
        }
        return 0;
    }
}

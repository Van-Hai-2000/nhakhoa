<?php

namespace common\components\shipping\giaohangtietkiem;

use common\components\shipping\ShippingMethod;
use common\components\shipping\giaohangtietkiem\helpers\GhtkConfig;
use common\components\shipping\giaohangtietkiem\helpers\GhtkConfig_DEV;
use common\components\shipping\giaohangtietkiem\helpers\Request;
use \Yii;

class ClaGiaoHangTietKiem extends ShippingMethod {

    public $configs = null;

    function __construct($options = []) {
        $this->loadOptions($options);
    }

    protected function loadOptions($options = array()) {
        $this->configs = new GhtkConfig();
        if (defined('YII_ENV') && YII_ENV==='dev') {
            $this->configs = new GhtkConfig_DEV();
        }
    }

    function calculDataPrice($options){
       return array(
            "pick_province" => $options['f_province'],
            "pick_district" => $options['f_district'],
            "pick_address" => isset($options['f_address']) ? $options['f_address']: '',
            "province" => $options['t_province'],
            "district" => $options['t_district'],
            "address" => isset($options['t_address']) ? $options['t_address']: '',
            "weight" => $options['weight'],
            "Length" =>  isset($options['length']) ? $options['length']: 10,
            "Width" =>  isset($options['width']) ? $options['width']: 110,
            "Height" =>  isset($options['height']) ? $options['height']: 20,
            //không bắt buộc
            "value" => 0,//price
        );
    }

    public function getStatus($id) {
        $data = [
            '-1' => Yii::t('app', 'Cancel'),
            '0' => Yii::t('app', 'Cancel'),
            '1' => Yii::t('app', 'ghnst_1'),
            '2' => Yii::t('app', 'ghnst_2'),
            '3' => Yii::t('app', 'ghnst_3'),
            '4' => Yii::t('app', 'ghnst_4'),
            '5' => Yii::t('app', 'ghnst_5'),
            '6' => Yii::t('app', 'ghnst_6'),
            '7' => Yii::t('app', 'ghnst_7'),
            '8' => Yii::t('app', 'ghnst_8'),
            '9' => Yii::t('app', 'ghnst_9'),
            '10' => Yii::t('app', 'ghnst_10'),
            '11' => Yii::t('app', 'ghnst_11'),
            '12' => Yii::t('app', 'ghnst_12'),
            '20' => Yii::t('app', 'ghnst_20'),
            '21' => Yii::t('app', 'ghnst_21'),
            '123' => Yii::t('app', 'ghnst_123'),
            '127' => Yii::t('app', 'ghnst_127'),
            '128' => Yii::t('app', 'ghnst_128'),
            '45' => Yii::t('app', 'ghnst_45'),
            '49' => Yii::t('app', 'ghnst_49'),
            '410' => Yii::t('app', 'ghnst_410'),
        ];
        return isset($data[$id]) ? $data[$id] : '';
    }

    public static function getSystemStatus($status) {
        $data = [
            '-1' => 0,
            '1' => 1,
            '2' => 1,
            '3' => 2,
            '4' => 3,
            '5' => 4,
            '6' => 4,
            '7' => 0,
            '8' => 2,
            '9' => 3,
            '10' => 3,
            '11' => 0,
            '12' => 2,
            '13' => 3,
            '20' => 3,
            '21' => 0,
            '123' => 3,
            '127' => 2,
            '128' => 2,
            '45' => 4,
            '49' => 3,
            '410' => 3,
        ];
        return isset($data[$status]) ? $data[$status] : '';
    }

    public static function getStatusId($id) {
        return $id;
    }

    public function getDataCreateOrder($order, $data_pr) {
        $shop_address = \common\models\shop\ShopAddress::getAddressByOrderShop($order);
        $address = \common\models\user\UserAddress::getDefaultAddressByUserId($order->user_id);
        $products = \common\models\order\OrderItem::getProductByShopId($order->id);
        $orderTotalShop = 0;
        $fee = \common\models\order\Order::checkPickMoney($order->id);
        foreach ($products as $item) {
            $orderTotalShop += $item['price'] * $item['quantity'];
            $options['data']['products'][] = [
                'name' => $item['name'],
                'weight' => $item['weight']/1000*$item['quantity'],
                'quantity' => $item['quantity']
            ];
        }
        $data = array(
            "pick_name" => $shop_address->name_contact ? $shop_address->name_contact : Yii::t('app', 'gcaeco_name'),
            "pick_address" => $shop_address->address.', '.$shop_address->ward_name,
            "pick_province" => $shop_address->province_name,
            "pick_district" => $shop_address->district_name,
            "pick_tel" => $shop_address->phone ? $shop_address->phone : '0000000000',
            "tel" => $address['phone'] ? $address['phone'] : '0000000000',
            "name" => $address['name_contact'] ? $address['name_contact'] : Yii::t('app', 'gcaeco_name'),
            "address" => $data_pr ? 'Liên hệ' : $address['address'].', '.$address['ward_name'],
            "province" => (isset($data_pr['province']) && $data_pr['province']) ? \common\models\Province::getNamebyId($data_pr['province']) : $address['province_name'],
            "district" => (isset($data_pr['district']) && $data_pr['district']) ? \common\models\District::getNamebyId($data_pr['district']) : $address['district_name'],
            "is_freeship" => "0",
            "pick_date" => date('Y-m-d', time()),
            "pick_money" => $fee ? $orderTotalShop : 0,
            "note" => "Tạo đơn hàng tự động qua API",
            "value" => 0,
            'id' => 'ORST'.$order->id,
        );
        $options['data']['order'] = $data;
       
        return $options['data'];
    }

    /**
     * Tính phí vận chuyển
     * 
     * @param type $options
     * @return array
     */
    function calculateFee($options = array()) {
        $request = new Request();
        $data = isset($options['data']) ? $options['data'] : array();
        $return = array();
        if (!$data) {
            return $return;
        }
        $response = $request->getFee($data);
        $return['success'] = isset($response['success']) ? $response['success'] : false;
        $return['message'] = isset($response['message']) ? $response['message'] : '';
        $return['fee'] = isset($response['fee']['fee']) ? $response['fee']['fee'] : '';
        $return['insurance_fee'] = isset($response['fee']['insurance_fee']) ? $response['fee']['insurance_fee'] : ''; // Phí bảo hiểm
        $return['info'] = isset($response['fee']) ? $response['fee'] : array(); // All info
        //
       return $return;
    }

    // Tạo hóa đơn vận chuyển
    function createOrder($options = array()) {
        $request = new Request();
        $data = isset($options['data']) ? $options['data'] : array();
        $return = array();
        if (!$data) {
            return $return;
        }
        $response = $request->createOrder($data);
        $return['success'] = isset($response['success']) ? $response['success'] : false;
        $return['message'] = isset($response['message']) ? $response['message'] : '';
        $return['order'] = isset($response['order']['label']) ? $response['order']['label'] : '';
        $return['fee'] = isset($response['order']['fee']) ? $response['order']['fee'] : '';
        $return['info'] = isset($response['order']) ? $response['order'] : array(); // All info
        //
       return $return;
    }

    //cong them
    // lấy thông tin hóa đơn
    function getInfoOrder($options = array()) {
        $request = new Request();
        $data = isset($options['data']) ? $options['data'] : array();
        $return = array();
        if (!$data) {
            return $return;
        }
        $response = $request->getInfoOrder($data);
        $return['success'] = isset($response['success']) ? $response['success'] : false;
        $return['message'] = isset($response['message']) ? $response['message'] : '';
        $return['info'] = isset($response['order']) ? $response['order'] : array(); // All info
        //
       return $return;
    }

    // lấy thông tin cập nhật hóa đơn
    function getInfoOrderUpdate($options = array()) {
        $request = new Request();
        $data = isset($options['data']) ? $options['data'] : array();
        $return = array();
        if (!$data) {
            return $return;
        }
        $response = $request->getInfoOrder($data);
        // print_r($response);
        $return['time'] = (isset($response['order']['modified'])) ? $response['order']['modified'] : date('Y-m-d h:i:s', time());
        $return['status'] =  (isset($response['order']['status']))  ? $response['order']['status'] : '';
        //
        // print_r($return);
       return $return;
    }

    // hủy hóa đơn
    function cancerOrder($options = array()) {
        $request = new Request();
        $data = isset($options['data']) ? $options['data'] : array();
        $return = array();
        if (!$data) {
            return $return;
        }
        $response = $request->cancerOrder($data);
        $return['success'] = isset($response['success']) ? $response['success'] : false;
        $return['message'] = isset($response['message']) ? $response['message'] : '';
        $return['info'] = isset($response['order']) ? $response['order'] : array(); // All info
        //
       return $return;
    }

    function updateStatusFromHook($options = array()) {
        $info = $this->getInfoOrder($options);
        if(isset($info['success']) && $info['success']) {
            $order = \common\models\order\Order::findByTransportId($options['data']['OrderCode']);
            if($order) {
                return \common\models\order\Order::updateStatusAuto($order->id);
            }
        }
        return false;
    }
    //cong them

}

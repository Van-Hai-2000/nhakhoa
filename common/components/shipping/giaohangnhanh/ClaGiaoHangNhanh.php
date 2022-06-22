<?php

namespace common\components\shipping\giaohangnhanh;

use common\components\shipping\ShippingMethod;
use common\components\shipping\giaohangnhanh\helpers\GhnConfig;
use common\components\shipping\giaohangnhanh\helpers\GhnConfig_DEV;
use common\components\shipping\giaohangnhanh\helpers\Request;
use \Yii;

class ClaGiaoHangNhanh extends ShippingMethod {

    public $configs = null;

    function __construct($options = []) {
        $this->loadOptions($options);
    }

    protected function loadOptions($options = array()) {
        $this->configs = new GhnConfig();
        if (defined('YII_ENV') && YII_ENV==='dev') {
            $this->configs = new GhnConfig_DEV();
        }
    }

    function calculDataPrice($options){
        $id_f = \common\models\Districts::findGhnId(trim($_POST['f_district']));
        $id_t = \common\models\Districts::findGhnId(trim($_POST['t_district']));
        if($id_f && $id_t) {
            return [
                "Weight" =>(int)$_POST['weight'],
                "Length" => isset($_POST['length']) ? (int)$_POST['length']: 10,
                "Width" => isset($_POST['width']) ? (int)$_POST['width']: 110,
                "Height" => isset($_POST['height']) ? (int)$_POST['height']: 20,
                "FromDistrictID" => (int)$id_f,
                "ToDistrictID" => (int)$id_t,
                "ServiceID" => '',
                "OrderCosts" =>  [
                        //["ServiceID" =>  100022] // sách sách dịch vụ bổ xung nếu có
                ],
                // "CouponCode"  => "COUPONTEST",//mã giam giá nếu có
                "InsuranceFee"  =>  0 // giá trị gói
            ];
        } else {
            return [];
        }
    }

    public function getStatus($id) {
       $data = [
            '1' => Yii::t('app', 'ReadyToPick'),
            '2' => Yii::t('app', 'Picking'),
            '3' => Yii::t('app', 'Storing'),
            '4' => Yii::t('app', 'Delivering'),
            '5' => Yii::t('app', 'Delivered'),
            '6' => Yii::t('app', 'Finish'),
            '7' => Yii::t('app', 'Returned'),
            '8' => Yii::t('app', 'WaitingToFinish'),
            '9' => Yii::t('app', 'Return'),
            '10' => Yii::t('app', 'LostOrder'),
            '0' => Yii::t('app', 'Cancel'),
        ];
        return isset($data[$id]) ? $data[$id] : '';
    }

    public function getSystemStatus($status) {
        $data = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 3,
            '5' => 3,
            '6' => 4,
            '7' => 0,
            '8' => 3,
            '9' => 0,
            '10' => 0,
            '0' => 0,
        ];
        return isset($data[$status]) ? $data[$status] : '';
    }

    public function getStatusId($id) {
        $data = [
            'ReadyToPick' => 1,
            'Picking' => 2,
            'Storing' => 3,
            'Delivering' => 4,
            'Delivered' => 5,
            'Finish' => 6,
            'Returned' => 7,
            'WaitingToFinish' => 8,
            'Return' => 9,
            'LostOrder' => 10,
            'Cancel' => 0,
        ];
        if(is_numeric($id)) {
            return isset($data[$id]) ? $data[$id] : $id;
        }
        return isset($data[$id]) ? $data[$id] : 0;
    }

    public function getDataCreateOrder($order, $data_pr) {
        // $shop = \common\models\shop\Shop::findOne($orderShop->shop_id);
        $shop_address = \common\models\shop\ShopAddress::getAddressByOrderShop($order);
        $address = \common\models\user\UserAddress::getDefaultAddressByUserId($order->user_id); 
        $fee = \common\models\order\Order::checkPickMoney($order->id);
        if($data_pr && isset($data_pr['district']) && $data_pr['district']) {
            $districtss = \common\models\District::findOne($data_pr['district']);
            $address['district_name'] = $districtss['name'];
            $address['latlng'] = $districtss['latlng'];
            $address['address'] = 'Liên hê địa chỉ cụ thể';
        }
        //get info 
        $info4 = \common\models\order\OrderItem::getInfo4($order->id);
        $weight = $info4['weight'] ? $info4['weight'] : 1;
        $length = $info4['length'] ? $info4['length'] : 10;
        $width = $info4['width'] ? $info4['width'] : 110;
        $height = $info4['height'] ? $info4['height'] : 20;
        $id_f = \common\models\Districts::findGhnId(trim($shop_address['district_name']));
        $id_t = \common\models\Districts::findGhnId(trim($address['district_name']));

        //change info
        $t_latlng = ($address['latlng']) ? explode(',', $address['latlng']) : 0;
        $f_latlng = ($shop_address['latlng']) ? explode(',', $shop_address['latlng']) : 0;
        $t_lat = 0;
        $t_lng = 0;
        $f_lat = 0;
        $f_lng = 0;
        if(count($t_latlng) == 2) {
            $t_lat = (float)$t_latlng[0];
            $t_lng = (float)$t_latlng[1];
        }
        if(count($f_latlng) == 2) {
            $f_lat = (float)$f_latlng[0];
            $f_lng = (float)$f_latlng[1];
        }
        $data=[
            "ServiceID" => '',
            "Weight" => $weight,
            "Width" => $width,
            "Length" => $length,
            "Height" => $height,
            "FromDistrictID" => $id_f,
            "ToDistrictID" => $id_t,
            "PaymentTypeID" => 2,
            "FromWardCode" => '',
            "ToWardCode" => '',
            "Note" =>  Yii::t('app', 'sendind_from_gcaeco'),
            "SealCode" => "tem niêm phong",
            "ExternalCode" => "",
            "ClientContactName" => $shop_address->name_contact ? $shop_address->name_contact : Yii::t('app', 'gcaeco_name'),
            "ClientContactPhone" => $shop_address->phone ? $shop_address->phone : '0000000000',
            "ClientAddress" => $shop_address['address'] ? $shop_address['address'] : '',
            "CustomerName" => $address['name_contact'] ? $address['name_contact'] : Yii::t('app', 'gcaeco_name'),
            "CustomerPhone" =>  $address['phone'] ? $address['phone'] : '0000000000',
            "ShippingAddress" => $address['address'] ? $address['address'] : '',
            "CoDAmount" => $fee ? $order->order_total : 0,
            "NoteCode" => 'XEMGHICHUKHACHHANG',
            "ClientHubID" => 0,
            "ToLatitude" => $t_lat,
            "ToLongitude" => $t_lng,
            "FromLat" => $f_lat,
            "FromLng" => $f_lng,
            "Content" => '',
            "CouponCode" => '',
            "CheckMainBankAccount" => false,
            "ShippingOrderCosts" => [],
            "ReturnContactName" => '',
            "ReturnContactPhone" => '',
            "ReturnAddress" => '',
            "ReturnDistrictCode" => '',
            "ExternalReturnCode" => '',
            "IsCreditCreate" => false,
        ];
        return $data;
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
        $return['success'] = (isset($response['code']) && $response['code'])  ? $response['code'] : false;
        $return['message'] = isset($response['msg']) ? $response['msg'] : '';
        $return['fee'] = isset($response['data']['DiscountFee']) ? $response['data']['DiscountFee'] : '';
        $return['insurance_fee'] = isset($response['data']['insurance_fee']) ? $response['data']['insurance_fee'] : ''; // Phí bảo hiểm
        $return['info'] = isset($response['data']) ? $response['data'] : array(); // All info
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
        // print_r($response);
        $return['success'] = (isset($response['code']) && $response['code'])  ? $response['code'] : false;
        $return['message'] =  isset($response['msg']) ? $response['msg'] : '';
        $return['order'] = isset($response['data']['OrderCode']) ? $response['data']['OrderCode'] : '';
        $return['fee'] = isset($response['data']['TotalServiceFee']) ? $response['data']['TotalServiceFee'] : '';
        $return['info'] =  isset($response['data']) ? $response['data'] : array(); // All info
        //
       return $return;
    }

    // lấy thông tin hóa đơn
    function getInfoOrder($options = array()) {
        $request = new Request();
        $data = isset($options['data']) ? $options['data'] : array();
        $return = array();
        if (!$data) {
            return $return;
        }
        $response = $request->getInfoOrder($data);
        // print_r($response);
        $return['success'] = (isset($response['code']) && $response['code'])  ? $response['code'] : false;
        $return['message'] =  isset($response['msg']) ? $response['msg'] : '';
        
        $return['info'] =  isset($response['data']) ? $response['data'] : array(); // All info
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
        $return['time'] = (isset($response['data']['EndPickTime'])) ? $response['data']['EndPickTime'] : date('Y-m-d h:i:s', time());
        $return['status'] =  (isset($response['data']['CurrentStatus']))  ? $response['data']['CurrentStatus'] : '';
        //
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
        // print_r($response);
        $return['success'] = (isset($response['code']) && $response['code'])  ? $response['code'] : false;
        $return['message'] =  isset($response['msg']) ? $response['msg'] : '';
        
        $return['info'] =  isset($response['data']) ? $response['data'] : array(); // All info
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

}

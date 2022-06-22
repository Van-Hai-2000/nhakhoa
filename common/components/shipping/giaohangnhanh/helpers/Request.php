<?php

namespace common\components\shipping\giaohangnhanh\helpers;
use Yii;

use common\components\shipping\giaohangnhanh\helpers\GhnConfig;
use common\components\shipping\giaohangnhanh\helpers\GhnConfig_DEV;

class Request {

    public $configs = null;
    public $apiBaseUrl = '';
    public $method = 'post';

    //
    function __construct($options = []) {
        $this->loadOptions($options);
    }

    protected function loadOptions($options = array()) {
        $this->configs = new GhnConfig();
         if (defined('YII_ENV') && YII_ENV==='dev') {
            $this->configs = new GhnConfig_DEV();
        }
        //
        $this->setApiBaseUrl($this->configs->apiBaseUrlTest);
        if ($this->configs->mode === 'real') {
            $this->setApiBaseUrl($this->configs->apiBaseUrlReal);
        }
    }

    //
    //
    public function sendRequest($url = null, $data = null) {
        if (!$url) {
            return false;
        }
        $data['token'] = $this->configs->token;
        $ch = curl_init();
        $urlInfo = parse_url($url);
        $head[] = "Token:" . $this->configs->token;
        $head[] = "Host: {$urlInfo['host']}";
        //
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
        if ($this->method == 'get') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } else {
            $head[] = "Content-Type: application/json";
            curl_setopt($ch, CURLOPT_POST, true);
            $data = json_encode($data);
            //
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //
        $response = curl_exec($ch);
        curl_close($ch);
        $result = $this->processResponse($response);
        //
        return $result;
    }

    // Lấy mã dịch vụ
    function getService($data = array(), $type = 1) {
        $this->method = 'post';
        $uri = '/api/v1/apiv3/FindAvailableServices';
        $requestUrl = $this->getApiBaseUrl() . $uri;
        //
        $response = $this->sendRequest($requestUrl, $data);
        if(isset($response['data'][$type]['ServiceID'])) {
            return $response['data'][$type]['ServiceID'];
        } else {
            if(isset($response['data'])) {
                return isset($response['data'][count($response['data']) -1]['ServiceID']) ? $response['data'][count($response['data']) -1]['ServiceID'] : 0;
            }
        }
        // print_r($response);
        return 0;
    }


    // Tinh phí vận chuyển
    function getFee($data = array()) {
        $this->method = 'post';
        $uri = '/api/v1/apiv3/CalculateFee';
        $requestUrl = $this->getApiBaseUrl() . $uri;
        //
        $data['ServiceID'] = self::getService($data, $data['ServiceID']);
        // echo $data['ServiceID'].'-';
        if($data['ServiceID']) {
            return $this->sendRequest($requestUrl, $data);
        } else {
            return [];
        }
        
    }

    // Tao hóa đơn
    function createOrder($data = array()) {
        $this->method = 'post';
        $uri = '/api/v1/apiv3/CreateOrder';
        $data['ServiceID'] = self::getService($data, $data['ServiceID']);
        $requestUrl = $this->getApiBaseUrl() . $uri;
        //
        if($data['ServiceID']) {
            return $this->sendRequest($requestUrl, $data);
        } else {
            return [];
        }
    }

    // Lấy thông tin hóa đơn
    function getInfoOrder($data = array()) {
        $this->method = 'post';
        $uri = '/api/v1/apiv3/OrderInfo';
        $requestUrl = $this->getApiBaseUrl() . $uri;
        //
        return $this->sendRequest($requestUrl, $data);
    }
    // Hủy hóa đơn
    function cancerOrder($data = array()) {
        $this->method = 'post';
        $uri = '/api/v1/apiv3/CancelOrder';
        $requestUrl = $this->getApiBaseUrl() . $uri;
        //
        return $this->sendRequest($requestUrl, $data);
    }

    /**
     * process reponse result
     * assign to some properties
     * @return void
     */
    public function processResponse($response) {
        return json_decode($response, true);
    }

    // ------------------------------ set, get -------------------------------
    function getConfigs() {
        return $this->configs;
    }

    function getApiBaseUrl() {
        return $this->apiUrl;
    }

    function setConfigs($configs) {
        $this->configs = $configs;
    }

    function setApiBaseUrl($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

}

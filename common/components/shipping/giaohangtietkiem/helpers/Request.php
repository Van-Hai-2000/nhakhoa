<?php

namespace common\components\shipping\giaohangtietkiem\helpers;

use common\components\shipping\giaohangtietkiem\helpers\GhtkConfig;
use common\components\shipping\giaohangtietkiem\helpers\GhtkConfig_DEV;

class Request {

    public $configs = null;
    public $apiBaseUrl = '';
    public $method = 'post';

    //
    function __construct($options = []) {
        $this->loadOptions($options);
    }

    protected function loadOptions($options = array()) {
        $this->configs = new GhtkConfig();
        if (defined('YII_ENV') && YII_ENV==='dev') {
            $this->configs = new GhtkConfig_DEV();
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

    // Tinh phí vận chuyển
    function getFee($data = array()) {
        $this->method = 'get';
        $uri = '/services/shipment/fee?';
        $requestUrl = $this->getApiBaseUrl() . $uri . http_build_query($data);
        //
        return $this->sendRequest($requestUrl, $data);
    }

    // Tao hóa đơn
    function createOrder($data = array()) {
        $this->method = 'post';
        $uri = '/services/shipment/order';
        $requestUrl = $this->getApiBaseUrl() . $uri;
        //
        return $this->sendRequest($requestUrl, $data);
    }

    //cong them
    function getInfoOrder($data = array()) {
        $this->method = 'get';
        $uri = '/services/shipment/v2/';
        $requestUrl = $this->getApiBaseUrl() . $uri . $data['OrderCode'];
        //
        return $this->sendRequest($requestUrl, $data);
    }

    //cong them
    function cancerOrder($data = array()) {
        $this->method = 'post';
        $uri = '/services/shipment/cancel/';
        $requestUrl = $this->getApiBaseUrl() . $uri . $data['OrderCode'];
        //
        return $this->sendRequest($requestUrl, $data);
    }


    // cong them

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

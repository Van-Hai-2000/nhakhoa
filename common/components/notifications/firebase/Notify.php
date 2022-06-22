<?php

/**
 * Notify class for send message to user's devices (ios, android)
 *
 * @author minhbn
 */

namespace common\components\notifications\firebase;

use Yii;
use common\components\notifications\MobileNotify;
use common\components\notifications\firebase\Config;

class Notify extends MobileNotify {

    protected $configs;
    protected $apiBaseUrl;

    function __construct($options = []) {
        $this->loadOptions($options);
    }

    function loadOptions($options = array()) {
        $configs = new Config();
        $this->setConfigs($configs);
        switch ($configs->mode) {
            case 'http': {
                    $this->setApiBaseUrl($configs->requestUrlHttp);
                }break;
        }
    }

    function send($deviceToken, $data = array()) {
        $apiUrl = $this->getApiBaseUrl();
        if (!$apiUrl) {
            return false;
        }
        //
        $fields = array();
        $fields['data'] = isset($data['message']) ? $data['message'] : [
            'largeIcon' => 'large_icon', // chưa cần
            'smallIcon' => 'small_icon', // chưa càn
        ];
        $fields['notification'] = isset($data['notification']) ? $data['notification'] : '';
//        if(!isset($fields['notification']['badge']) || (int)$fields['notification']['badge']===0){
//            $fields['notification']['badge'] = 1; // hien thi so luong noti do do cho app (hien thi 1, 2 thi thien thị 2)
//        }
        if (is_array($deviceToken)) {
            $fields['registration_ids'] = $deviceToken;
        } else {
            $fields['to'] = $deviceToken;
        }
        return $this->sendRequest($apiUrl, $fields);
    }

    //
    public function sendRequest($url = null, $data = null) {
        if (!$url) {
            return false;
        }
        $configs = $this->getConfigs();
        $ch = curl_init();
        $urlInfo = parse_url($url);
        $head[] = "Authorization:key=" . $configs->legacyServerKey;
        //$head[] = "Host: {$urlInfo['host']}";
        //
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds

        $head[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        //
        curl_setopt($ch, CURLOPT_POST, true);
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //
        $response = curl_exec($ch);
        curl_close($ch);
        $result = $this->processResponse($response);
        //
        return $result;
    }

    /**
     * process reponse result
     * assign to some properties
     * @return void
     */
    public function processResponse($response) {
        return json_decode($response, true);
    }

    // --------------------------------------------------- seter and getter -----------------------------------------------------------
    function getConfigs() {
        return $this->configs;
    }

    function getApiBaseUrl() {
        return $this->apiBaseUrl;
    }

    function setConfigs($configs) {
        $this->configs = $configs;
    }

    function setApiBaseUrl($apiBaseUrl) {
        $this->apiBaseUrl = $apiBaseUrl;
    }

    // ---------------------------------------------------End seter and getter -----------------------------------------------------------
}

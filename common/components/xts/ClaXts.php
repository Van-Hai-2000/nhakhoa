<?php

namespace common\components\xts;

use Yii;

class ClaXts
{

    const URL = 'https://api.xts.vn';
    public $code = '';
    public $method = 'get';

    function __construct($shop_id)
    {
        $this->getCode($shop_id);
    }

    public function getCode($shop_id)
    {
        Yii::$app->session->open();
        if (isset($_SESSION['list_code_xts_ss'][$shop_id]) && $_SESSION['list_code_xts_ss'][$shop_id]) {
            $this->code = $_SESSION['list_code_xts_ss'][$shop_id];
        } else {
            $dt = \common\models\product\CerXtsShop::find()->where(['cer_xts_id' => 1, 'shop_id' => $shop_id])->one();
            if ($dt) {
                $this->code = $this->getCodeApi($dt->attributes);
            }
        }
        return $this->code;
    }

    public function setCode($shop_id, $code)
    {
        Yii::$app->session->open();
        $_SESSION['list_code_xts_ss'][$shop_id] = $code;
        $this->code = $code;
        return $this->code;
    }

    public function getCodeApi($options)
    {
        $api = '/api/login';
        $url = self::URL . $api;
        $this->method = 'post';
        $result = $this->sendRequestLogin($url, ['UserName' => $options['username'], 'Password' => $options['password']]);
        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '200') {
            return $this->setCode($options['shop_id'], $result['ApplicationTokenKey']);
        }
        return '';
    }

    public function getOptionsCodeDiary()
    {
        $api = '/api/p/diaries';
        $url = self::URL . $api;
        $this->method = 'get';
        $result = $this->sendRequest($url, []);
        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '200') {
            return array_column($result['ResponseData'], 'Name', 'Code');
        }
        return [];
    }

    public function getInfoCompany()
    {
        $api = '/api/p/companies';
        $url = self::URL . $api;
        $this->method = 'get';
        $result = $this->sendRequest($url, []);
        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '200') {
            return $result['ResponseData'];
        }
        return [];
    }

    public function getDiaryDetail($code)
    {
        $api = '/api/p/diaries/';
        $url = self::URL . $api . $code;
        $this->method = 'get';
        $result = $this->sendRequest($url, []);
        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '200') {
            return $result['ResponseData'];
        }
        return [];
    }

    public function sendRequestLogin($url = null, $data = null)
    {
        if (!$url) {
            return false;
        }
        $ch = curl_init();
        $urlInfo = parse_url($url);
        // $head[] = "X-API-KEY:" . self::X_API_KEY;
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

    public function processResponse($response)
    {
        return json_decode($response, true);
    }

    public function sendRequest($url = null, $data = null)
    {
        if (!$url) {
            return false;
        }
        $ch = curl_init();
        $urlInfo = parse_url($url);
        $head[] = "Authorization: Bearer " . $this->code;
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
}

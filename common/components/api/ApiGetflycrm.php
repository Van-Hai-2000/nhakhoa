<?php

namespace common\components\api;

use Yii;


/*
 * Class for create and show menu
 */

class ApiGetflycrm
{

    const X_API_KEY = 'tQUBhgFT7tSGOqZqII5XhLuG1qBmy7'; //Trang chính
    const URL = 'https://gca.getflycrm.com';
    public $method = 'get';

    static function addAccount($user)
    {
        // $user = \common\models\User::findOne(69);
        // $user->phone = '0965875662';
        $data = [
            'account' => [
                "account_name" => $user->username,
                "phone_office" => $user->phone,
                "email" => $user->email,
                "gender" => $user->sex ? 2 : 1,
                "billing_address_street" => $user->address,
                "birthday" => $user->birthday ? date('d/m/Y', $user->birthday) : '',
                "account_type" => 1,
                "industry" => null,
                "country_id" => 1,
                "province_id" => $user->province_id,
                "district_id" => $user->district_id,
            ],
            'contacts' => [
                [
                    "first_name" => $user->username,
                    "email" => $user->email,
                    "phone_mobile" => $user->phone,
                    "title" => "Người dùng OCOP"
                ],
            ],
            'referer' => [
                // "utm_source" => "https://getfly.vn",
                // "utm_campaign" => "GetflyWebsite",
                "utm_source" => __SERVER_NAME,
                "utm_campaign" => "OCOP"
            ],
            'custom_fields' => [
                "id_social" => $user->id_social,
                "avatar" => $user->avatar_name ? \common\components\ClaHost::getImageHost() . $user->avatar_path . $user->avatar_name : '',
            ],
        ];
        $api = new self();
        $api->method = 'post';
        $uri = '/api/v3/account/';
        $requestUrl = self::URL . $uri;
        //
        return $api->sendRequest($requestUrl, $data);
    }

    public function sendRequest($url = null, $data = null)
    {
        if (!$url) {
            return false;
        }
        $ch = curl_init();
        $urlInfo = parse_url($url);
        $head[] = "X-API-KEY:" . self::X_API_KEY;
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
}

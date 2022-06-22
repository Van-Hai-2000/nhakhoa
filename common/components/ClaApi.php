<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/14/2021
 * Time: 2:06 PM
 */


namespace common\components;

class ClaApi
{
    const URL_QRCODE = 'http://testqrcode.nanoweb.vn/';

    static function getApi($url)
    {
        $url = self::URL_QRCODE . $url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    static function postApi($url, $request)
    {
        $payload = json_encode($request);
        $url = self::URL_QRCODE.$url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );

        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }
}
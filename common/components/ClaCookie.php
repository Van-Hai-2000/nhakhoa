<?php

namespace common\components;
use Yii;
/*
 * Class for create and show menu
 */

class ClaCookie {
    const DOMAIN = 'ocopmart.org';
    const DEFAULT_EXPIRE_COOKIE = 30; // 30 days

    public static function getNameCookieShopAddress($shop_id) {
        return 'NameCookieShopAddress_'.$shop_id;
    }

    public static function getValueCookieShopAddress($shop_id) {
        $name = self::getNameCookieShopAddress($shop_id);
        if(isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return false;
    }

    public static function getNameCookieShopTransport($shop_id) {
        return 'NameCookieShopTransport_'.$shop_id;
    }

    public static function getValueCookieShopTransport($shop_id) {
        $name = self::getNameCookieShopTransport($shop_id);
        if(isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return false;
    }

}

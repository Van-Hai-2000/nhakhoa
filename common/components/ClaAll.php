<?php

namespace common\components;

use Yii;

/*
 * Class for create and show menu
 */

class ClaAll
{

    public static function getEmbedToLink($link)
    {
        $link = str_replace('https://youtu.be/', 'https://www.youtube.com/watch?v=', $link);
        $youtube = new \common\components\ClaYoutube($link);
        $yinfo = $youtube->getEmebed();
        if ($yinfo) {
            return isset($yinfo['embed_link']) ? $yinfo['embed_link'] : $link;
        }
        return $link;
    }

    public static function getTextTime($time_stemp)
    {
        $hour = $time_stemp / 3600;
        return ($hour / 24 > 1) ? CEIL($hour / 24) . ' ngày' : ($hour >= 1 ? $hour . ' giờ' : CEIL($hour * 60) . ' phút');
    }

    public static function getTextTimeTG($time_stemp)
    {
        return $time_stemp > 0 ? ' Sau ' . self::getTextTime($time_stemp) . ' tính từ thời điểm đơn hàng được xác nhận "Đã giao" số ' . __VOUCHER . ' này sẽ được chuyển vào tài khoản của quý khách nếu không có khiếu lại của khách hàng.' : ' Số ' . __VOUCHER . ' này sẽ được chuyển vào tài khoản của quý khách khi đơn hàng được xác nhận "Đã giao"';
    }

    static function setAdmin()
    {
        $name = 'smin_check_bke';
        setcookie($name, time(), time() + (86400 * 30), "/");
    }

    static function isAdmin()
    {
        return (isset($_COOKIE['smin_check_bke']) &&  $_COOKIE['smin_check_bke']);
    }

    function getUserID()
    {
        return (\Yii::$app->id == 'app-backend' ? 'A' : '') . \Yii::$app->user->id;
    }
}

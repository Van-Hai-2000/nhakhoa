<?php

namespace common\components;

use Yii;
use common\components\ClaMobileDetect;

/**
 * @description 
 *
 * @author hungtm
 */
class ClaSite {

    /**
     * return current server name
     */
    public static function getServerName() {
        $servername = Yii::$app->request->serverName;
        $servername = str_replace('www.', '', $servername);
        return $servername;
    }

    /**
     * Kiểm tra xem trang được vào bằng mobile hay không
     */
    public static function isMobile() {
        if(self::isIpad()) {
            return false;
        } 
        $mobile = new ClaMobileDetect();
        return $mobile->isMobile();
    }

    public static function isActiceApp() {
        $mobile = new ClaMobileDetect();
        return $mobile->isActiceApp();
    }

    public static function isIpad() {
        \Yii::$app->session->open();
        $screen = '0,0';
        if(isset($_SESSION['app_screen_doing'])) {
            $screen = $_SESSION['app_screen_doing'];
        } else {
            if(isset($_GET['screen_start_mobile'])) {
                $screen = $_GET['screen_start_mobile'];
                $_SESSION['app_screen_doing'] = $_GET['screen_start_mobile'];
            }
        }
        $screen = explode(',', $screen);
        if($screen[0] >= 768 && $screen[0] <=780) {
            return true;
        }
        return false;
    }
}

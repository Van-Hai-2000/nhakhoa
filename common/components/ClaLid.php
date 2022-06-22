<?php

namespace common\components;

use common\models\SiteIntroduce;
use common\models\Social;
use Yii;
use common\models\Province;
use common\models\District;
use common\models\Ward;
use common\models\Siteinfo;

/*
 * Class for create and show menu
 */

class ClaLid
{

    //
    const DOMAIN = __NAME;
    const DEFAULT_EXPIRE_COOKIE = 30; // 30 days
    //
    const PRODUCT_RELATION = 1;
    const STATUS_ACTIVED = 1;
    const STATUS_DEACTIVED = 0;
    const STATUS_CRAWLER = 2; // Mới crawler
    const STATUS_WAITING = 2; // Chờ đặt cọc
    //
    const DEFAULT_LIMIT = 40;
    const DEFAULT_ORDER = 'id DESC';
    // SET KEY CACHE
    const KEY_PROVINCE = 'province_';
    const KEY_DISTRICT = 'district_';
    const KEY_WARD = 'ward_';
    //
    const KEY_SITE_INFO = 'siteinfo';
    const KEY_SOCIAL_INFO = 'socialinfo';
    const KEY_INTRODUCE = 'socialintroduct';
    const KEY_CONFIG_COIN = 'KEY_CONFIG_COIN';

    const ROOT_SITE_ID = 1;
    //
    const KEY_MENU = 'menu_group_';
    //
    const LANGUAGE_VI = 'vi';
    const LANGUAGE_EN = 'en';
    const PAGE_VAR = 'page';
    const PAGE_SIZE_VAR = 'pageSize';
    const PAGE_SORT = 'sort';
    // const API_KEY = 'AIzaSyCTzEPYD5pmP22o0LN99IQoMbfLzcekELA';
    const API_KEY = 'AIzaSyDcb3gy1mSYnaC7Ol2iVZIIXb4Oc-nQYDc'; //Giành cho main 
    const KEY_FILTER_CHAR = 'key_filter_char';


    public static function getSiteinfo()
    {
        $cache = Yii::$app->cache;
        $siteinfo = $cache->get(self::KEY_SITE_INFO);
        if ($siteinfo === false) {
            $siteinfo = Siteinfo::findOne(self::ROOT_SITE_ID);
            $cache->set(self::KEY_SITE_INFO, $siteinfo);
        }
        return $siteinfo;
    }

    public static function getConfigCoin()
    {
        $cache = Yii::$app->cache;
        $tg = $cache->get(self::KEY_CONFIG_COIN);
        if ($tg === false) {
            $tg = \common\models\gcacoin\Config::find()->one();
            $cache->set(self::KEY_CONFIG_COIN, $tg);
        }
        return $tg;
    }

    public static function getSiteIntroduce()
    {
        $cache = Yii::$app->cache;
        $siteIntroduce = $cache->get(self::KEY_INTRODUCE);
        if ($siteIntroduce === false) {
            $siteIntroduce = SiteIntroduce::findOne(1);
            $cache->set(self::KEY_INTRODUCE, $siteIntroduce);
        }
        return $siteIntroduce;
    }

    public static function getSocialInfo()
    {
        $cache = Yii::$app->cache;
        $socialinfo = $cache->get(self::KEY_SOCIAL_INFO);
        if ($socialinfo === false) {
            $socialinfo = Social::findOne(self::ROOT_SITE_ID);
            $cache->set(self::KEY_SOCIAL_INFO, $socialinfo);
        }
        return $socialinfo;
    }

    public static function optionsStatus()
    {
        return [
            self::STATUS_ACTIVED => 'Hiển thị',
            self::STATUS_DEACTIVED => 'Ẩn'
        ];
    }

    public static function optionsYesNo()
    {
        return [
            self::STATUS_ACTIVED => 'Có',
            self::STATUS_DEACTIVED => 'Không'
        ];
    }

    public static function getAddress($province_id = 0, $district_id = 0, $ward_id = 0)
    {
        $cache = Yii::$app->cache;
        //
        if ($ward_id) {
            $key_ward = self::KEY_WARD . $ward_id;
            $ward = $cache->get($key_ward);
            if ($ward === false) {
                $ward = Ward::findOne($ward_id);
                $cache->set($key_ward, $ward);
            }
        }
        if ($district_id) {
            $key_district = self::KEY_DISTRICT . $district_id;
            $district = $cache->get($key_district);
            if ($district === false) {
                $district = District::findOne($district_id);
                $cache->set($key_district, $district);
            }
        }
        if ($province_id) {
            $key_province = self::KEY_PROVINCE . $province_id;
            $province = $cache->get($key_province);
            if ($province === false) {
                $province = Province::findOne($province_id);
                $cache->set($key_province, $province);
            }
        }
        //
        $address = [];
        if (isset($ward->name) && $ward->name) {
            array_push($address, $ward->name);
        }
        if (isset($district->name) && $district->name) {
            array_push($address, $district->name);
        }
        if (isset($province->name) && $province->name) {
            array_push($address, $province->name);
        }
        return !empty($address) ? implode(' - ', $address) : '';
    }

    public static function getProvince($province_id = 0)
    {
        $cache = Yii::$app->cache;
        if ($province_id) {
            $key_province = self::KEY_PROVINCE . $province_id;
            $province = $cache->get($key_province);
            if ($province === false) {
                $province = Province::findOne($province_id);
                $cache->set($key_province, $province);
            }
        }
        return (isset($province) && $province) ? $province->attributes : [];
    }

    public static function alertMsg($msg, $url = '')
    {
        echo '<script type="text/javascript">';
        echo 'alert("' . $msg . '");';
        if ($url != '') {
            echo 'location.href = "' . $url . '";';
        }
        echo '</script>';
    }

    public static function generateCodeDetail($item)
    {
        return $item['code'] . '_' . $item['color'] . '_' . $item['size'];
    }

    public static function getDiscount($price_market, $price)
    {
        // $price_market = str_replace('.', '', $price_market);
        // $price = str_replace('.', '', $price);
        return ($price_market && $price) ? round(($price_market - $price) / ($price_market / 100)) : '';
    }

    public static function getCurrentLanguage()
    {
        $language = self::LANGUAGE_VI;
        Yii::$app->language = self::LANGUAGE_VI;
        if (Yii::$app->getRequest()->getCookies()->has('lang')) {
            Yii::$app->language = $language = Yii::$app->getRequest()->getCookies()->getValue('lang');
        }

        return $language;
    }

    public static function getDataByLanguage($data_vi, $data_en)
    {
        $language = self::getCurrentLanguage();
        $data = '';
        if ($language == self::LANGUAGE_VI) {
            $data = $data_vi;
        } else if ($language == self::LANGUAGE_EN) {
            if ($data_en) {
                $data = $data_en;
            } else {
                $data = $data_vi;
            }
        }
        return $data;
    }

    /**
     * Function set cookie
     * @param type $cookie_name
     * @param type $value
     * @param type $expire
     */
    public static function setCookie($cookie_name, $value, $expire = 0)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $cookie_name,
            'value' => $value,
            'expire' => time() + 86400 * $expire,
        ]));
    }

    /**
     * Remove cookie
     * */
    public static function removeCookie($cookie_name)
    {
        $cookies = Yii::$app->response->cookies;
        unset($cookies[$cookie_name]);
    }

    /**
     * Function get cookie
     * @param type $cookie_name
     * @return type
     */
    public static function getCookie($cookie_name)
    {
        $cookies = Yii::$app->request->cookies;
        $data = '';
        if ($cookies->has($cookie_name)) {
            $data = $cookies->getValue($cookie_name);
        }
        return $data;
    }

    public static function getSessionId()
    {
        $session = Yii::$app->session;
        // if session is not open, open session
        if (!$session->isActive) {
            $session->open();
        }
        //
        $session_id = Yii::$app->session->getId();
        //
        return $session_id;
    }

    public static function getUserIdFromShopId($shop_id)
    {
        $shop = \common\models\shop\Shop::findOne($shop_id);
        if ($shop === NULL) {
            return 0;
        }
        return $shop->user_id;
    }

    public static function getLocaltionDefault()
    {
        $cookie_name = 'ADDRESS_DEFAUT2';
        // $location = ClaLid::getCookie($cookie_name);
        $location = 0;
        if (!$location) {
            $location = \common\models\user\UserAddress::getDefaultAddressOject();
            ClaLid::setLocaltionDefault($location);
        }
        if (!$location) {
            $location = new \common\models\user\UserAddress();
            $location->id = 0;
            $location->name_contact = '';
            $location->phone = '';
            $location->province_id = '';
            $location->district_id = '';
            $location->ward_id = '';
            $location->address = '';
            $location->isdefault = '';
            $location->province_name = '';
            $location->district_name = '';
            $location->ward_name = '';
            ClaLid::setLocaltionDefault($location);
        }
        return $location;
    }

    public static function setLocaltionDefault($location)
    {
        $cookie_name = 'ADDRESS_DEFAUT2';
        ClaLid::setCookie($cookie_name, $location, $expire = 30);
    }

    public static function resetLocaltionDefault()
    {
        $cookie_name = 'ADDRESS_DEFAUT2';
        ClaLid::setCookie($cookie_name, null, $expire = 30);
        return ClaLid::getLocaltionDefault();
    }

    public static function getProvinceDefault()
    {
        $local = ClaLid::getLocaltionDefault();
        if (!$local->province_id && $local->province_name) {
            $local->province_name = trim($local->province_name);
            if ($local->province_id = Province::getIdbyName($local->province_name)) {
                ClaLid::setLocaltionDefault($local);
            }
        }
        // echo $local->province_id.$local->province_name;
        return $local->province_id;
    }

    public static function getLatlngDefault()
    {
        if ($location = self::getLocaltionDefault()) {
            return $location['latlng'] ? $location['latlng'] : '21.03139,105.8525';
        }
        return '21.03139,105.8525';
    }
    public static function getFullTextLatlngDefault()
    {
        if ($location = self::getLocaltionDefault()) {
            if ($location['latlng']) {
                return $location['address'] . ', ' . $location['ward_name'] . ', ' . $location['district_name'] . ', ' . $location['province_name'];
            }
        }
        return '';
    }

    public static function getFilterChar()
    {
        $cache = Yii::$app->cache;
        $filter = $cache->get(self::KEY_FILTER_CHAR);
        if ($filter === false) {
            $filter = (new \yii\db\Query())->select('*')
                ->from('filter_char')->all();
            $cache->set(self::KEY_FILTER_CHAR, $filter);
        }
        return $filter;
    }

    public static function getIdQc($index)
    {
        $arr = [
            'index' => 9,
            'index-cat' => 10,
        ];
        return isset($arr[$index]) ? $arr[$index] : 8;
    }

    // Function to get the client IP address
    static function getClientIp()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    static function getOS($user_agent = null)
    {
        if (!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
        $os_array = [
            'windows nt 10' => 'Windows 10',
            'windows nt 6.3' => 'Windows 8.1',
            'windows nt 6.2' => 'Windows 8',
            'windows nt 6.1|windows nt 7.0' => 'Windows 7',
            'windows nt 6.0' => 'Windows Vista',
            'windows nt 5.2' => 'Windows Server 2003/XP x64',
            'windows nt 5.1' => 'Windows XP',
            'windows xp' => 'Windows XP',
            'windows nt 5.0|windows nt5.1|windows 2000' => 'Windows 2000',
            'windows me' => 'Windows ME',
            'windows nt 4.0|winnt4.0' => 'Windows NT',
            'windows ce' => 'Windows CE',
            'windows 98|win98' => 'Windows 98',
            'windows 95|win95' => 'Windows 95',
            'win16' => 'Windows 3.11',
            'mac os x 10.1[^0-9]' => 'Mac OS X Puma',
            'macintosh|mac os x' => 'Mac OS X',
            'mac_powerpc' => 'Mac OS 9',
            'linux' => 'Linux',
            'ubuntu' => 'Linux - Ubuntu',
            'iphone' => 'iPhone',
            'ipod' => 'iPod',
            'ipad' => 'iPad',
            'android' => 'Android',
            'blackberry' => 'BlackBerry',
            'webos' => 'Mobile',
            '(media center pc).([0-9]{1,2}\.[0-9]{1,2})' => 'Windows Media Center',
            '(win)([0-9]{1,2}\.[0-9x]{1,2})' => 'Windows',
            '(win)([0-9]{2})' => 'Windows',
            '(windows)([0-9x]{2})' => 'Windows',
            // Doesn't seem like these are necessary...not totally sure though..
            //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
            //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg
            'Win 9x 4.90' => 'Windows ME',
            '(windows)([0-9]{1,2}\.[0-9]{1,2})' => 'Windows',
            'win32' => 'Windows',
            '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})' => 'Java',
            '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}' => 'Solaris',
            'dos x86' => 'DOS',
            'Mac OS X' => 'Mac OS X',
            'Mac_PowerPC' => 'Macintosh PowerPC',
            '(mac|Macintosh)' => 'Mac OS',
            '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}' => 'SunOS',
            '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}' => 'BeOS',
            '(risc os)([0-9]{1,2}\.[0-9]{1,2})' => 'RISC OS',
            'unix' => 'Unix',
            'os/2' => 'OS/2',
            'freebsd' => 'FreeBSD',
            'openbsd' => 'OpenBSD',
            'netbsd' => 'NetBSD',
            'irix' => 'IRIX',
            'plan9' => 'Plan9',
            'osf' => 'OSF',
            'aix' => 'AIX',
            'GNU Hurd' => 'GNU Hurd',
            '(fedora)' => 'Linux - Fedora',
            '(kubuntu)' => 'Linux - Kubuntu',
            '(ubuntu)' => 'Linux - Ubuntu',
            '(debian)' => 'Linux - Debian',
            '(CentOS)' => 'Linux - CentOS',
            '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)' => 'Linux - Mandriva',
            '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)' => 'Linux - SUSE',
            '(Dropline)' => 'Linux - Slackware (Dropline GNOME)',
            '(ASPLinux)' => 'Linux - ASPLinux',
            '(Red Hat)' => 'Linux - Red Hat',
            // Loads of Linux machines will be detected as unix.
            // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
            //'X11'=>'Unix',
            '(linux)' => 'Linux',
            '(amigaos)([0-9]{1,2}\.[0-9]{1,2})' => 'AmigaOS',
            'amiga-aweb' => 'AmigaOS',
            'amiga' => 'Amiga',
            'AvantGo' => 'PalmOS',
            //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
            //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
            //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
            '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})' => 'Linux',
            '(webtv)/([0-9]{1,2}\.[0-9]{1,2})' => 'WebTV',
            'Dreamcast' => 'Dreamcast OS',
            'GetRight' => 'Windows',
            'go!zilla' => 'Windows',
            'gozilla' => 'Windows',
            'gulliver' => 'Windows',
            'ia archiver' => 'Windows',
            'NetPositive' => 'Windows',
            'mass downloader' => 'Windows',
            'microsoft' => 'Windows',
            'offline explorer' => 'Windows',
            'teleport' => 'Windows',
            'web downloader' => 'Windows',
            'webcapture' => 'Windows',
            'webcollage' => 'Windows',
            'webcopier' => 'Windows',
            'webstripper' => 'Windows',
            'webzip' => 'Windows',
            'wget' => 'Windows',
            'Java' => 'Unknown',
            'flashget' => 'Windows',
            // delete next line if the script show not the right OS
            //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
            'MS FrontPage' => 'Windows',
            '(msproxy)/([0-9]{1,2}.[0-9]{1,2})' => 'Windows',
            '(msie)([0-9]{1,2}.[0-9]{1,2})' => 'Windows',
            'libwww-perl' => 'Unix',
            'UP.Browser' => 'Windows CE',
            'NetAnts' => 'Windows',
        ];

        // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
        $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
        $arch = preg_match($arch_regex, $user_agent) ? '64' : '32';

        foreach ($os_array as $regex => $value) {
            if (preg_match('{\b(' . $regex . ')\b}i', $user_agent)) {
                return $value . ' x' . $arch;
            }
        }

        return 'Unknown';
    }
}

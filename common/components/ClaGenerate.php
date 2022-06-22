<?php

namespace common\components;

use common\components\ClaSite;

/**
 * @description Process string
 *
 * @author hungtm
 */
class ClaGenerate
{

    static protected $key = 'keyocop';
    public $numeric = array(
        1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 0 => 0
    );
    public $alpha = array(
        1 => "a", 30 => "r", 55 => "I",
        2 => "b", 31 => "s",
        3 => "c", 32 => "t",
        4 => "d", 33 => "u",
        5 => "e", 34 => "v",
        10 => "f", 35 => "w",
        11 => "g", 40 => "x",
        12 => "h", 41 => "y",
        13 => "i", 42 => "z",
        14 => "j", 43 => "A",
        15 => "k", 44 => "B",
        20 => "l", 45 => "C",
        21 => "m", 50 => "D",
        22 => "n", 51 => "E",
        23 => "o", 52 => "F",
        24 => "p", 53 => "G",
        25 => "q", 54 => "H"
    );
    public $alpha_re = array(
        "a" => "01", "r" => "30", "I" => "55",
        "b" => "02", "s" => "31",
        "c" => "03", "t" => "32",
        "d" => "04", "u" => "33",
        "e" => "05", "v" => "34",
        "f" => "10", "w" => "35",
        "g" => "11", "x" => "40",
        "h" => "12", "y" => "41",
        "i" => "13", "z" => "42",
        "j" => "14", "A" => "43",
        "k" => "15", "B" => "44",
        "l" => "20", "C" => "45",
        "m" => "21", "D" => "50",
        "n" => "22", "E" => "51",
        "o" => "23", "F" => "52",
        "p" => "24", "G" => "53",
        "q" => "25", "H" => "54",
    );

    /**
     * @author hungtm
     * generate a random sort code
     * @param int $lenght lenght of string code that you want to generate
     *            - Min is 6
     * @return string
     */
    public static function randomCode($lenght = 6)
    {
        $g = new ClaGenerate();
        if ($lenght >= 6) {
            $string = '';
            $meger = $g->numeric + $g->alpha_re;
            for ($i = 0; $i <= $lenght; $i++) {
                $string = $string . array_rand($meger);
            }
            return $string;
        }
        return false;
    }

    /*
     * @author: hungtm
     * @email: hungtm.0712@gmail.com
     * @date: 02/01/2014 
     */

    // encrypt 
    static function encrypt($string)
    {
        $key = md5(ClaSite::getServerName());
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return self::encode_base64($result);
    }

    // decrypt
    static function decrypt($string)
    {
        $key = md5(ClaSite::getServerName());
        $result = '';
        $string = self::decode_base64($string);
        //
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    // permanent encrypt
    static function pe_encrypt($string)
    {
        $key = md5(Yii::app()->user->id) . self::$key;
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return self::encode_base64($result);
    }

    // decrypt
    static function pe_decrypt($string)
    {
        $key = md5(Yii::app()->user->id) . self::$key;
        $result = '';
        $string = self::decode_base64($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    //
    //base64
    static function encode_base64($sData)
    {
        $sBase64 = base64_encode($sData);
        return str_replace('=', '', strtr($sBase64, '+/', '-_'));
    }

    static function decode_base64($sData)
    {
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64 . '==');
    }

    /**
     * encryping Password
     * @param string $password
     * @param string $salt 
     * @return string
     */
    public static function encrypPassword($password, $salt = '')
    {
        $n = $salt . sha1($password);
        return md5($n);
    }

    /**
     * get uniquie
     */
    static function getUniqueCode($options = array())
    {
        //
        $prefix = '';
        if (isset($options['prefix']))
            $prefix = $options['prefix'];
        //
        return uniqid($prefix . time() . rand(0, 99999), false);
    }

    /**
     * return quoteValue
     * @param type $str
     * @return type
     */
    static function quoteValue($str = '')
    {
        if ($str === NULL)
            $str = '';
        $str = Yii::app()->db->quoteValue($str);
        $str = str_replace('\u', '\\u', $str);
        return $str;
    }

    /**
     * return br to nl
     * @param type $string
     * @return type
     */
    static function nl2br($string)
    {
        $string = str_replace(array("\r\n", "\n\r", "\n", "\r", chr(30), chr(155), PHP_EOL), "<br />", $string);
        return $string;
    }

    /**
     * return br to nl
     * @param type $string
     * @return type
     */
    static function br2nl($string, $separator = PHP_EOL)
    {
        $separator = in_array($separator, array("\r\n", "\n\r", "\n", "\r", chr(30), chr(155), PHP_EOL)) ? $separator : PHP_EOL;  // Checks if provided $separator is valid.
        return preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $string);
    }

    /**
     * remove br of str
     * @param type $string
     * @return type
     */
    static function removeBr($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', '', $string);
    }
}

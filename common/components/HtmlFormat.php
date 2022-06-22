<?php

namespace common\components;

class HtmlFormat {

    public static function stripUnicode($str, $case = 0) {
        if (!$str)
            return false;
        $utf8_lower_accents = array(
            'à' => 'a', 'ô' => 'o', 'ď' => 'd', 'ḟ' => 'f', 'ë' => 'e', 'š' => 's', 'ơ' => 'o',
            'ß' => 'ss', 'ă' => 'a', 'ř' => 'r', 'ț' => 't', 'ň' => 'n', 'ā' => 'a', 'ķ' => 'k',
            'ŝ' => 's', 'ỳ' => 'y', 'ņ' => 'n', 'ĺ' => 'l', 'ħ' => 'h', 'ṗ' => 'p', 'ó' => 'o',
            'ú' => 'u', 'ě' => 'e', 'é' => 'e', 'ç' => 'c', 'ẁ' => 'w', 'ċ' => 'c', 'õ' => 'o',
            'ṡ' => 's', 'ø' => 'o', 'ģ' => 'g', 'ŧ' => 't', 'ș' => 's', 'ė' => 'e', 'ĉ' => 'c',
            'ś' => 's', 'î' => 'i', 'ű' => 'u', 'ć' => 'c', 'ę' => 'e', 'ŵ' => 'w', 'ṫ' => 't',
            'ū' => 'u', 'č' => 'c', 'ö' => 'o', 'è' => 'e', 'ŷ' => 'y', 'ą' => 'a', 'ł' => 'l',
            'ų' => 'u', 'ů' => 'u', 'ş' => 's', 'ğ' => 'g', 'ļ' => 'l', 'ƒ' => 'f', 'ž' => 'z',
            'ẃ' => 'w', 'ḃ' => 'b', 'å' => 'a', 'ì' => 'i', 'ï' => 'i', 'ť' => 't',
            'ŗ' => 'r', 'ä' => 'a', 'í' => 'i', 'ŕ' => 'r', 'ê' => 'e', 'ü' => 'u', 'ò' => 'o',
            'ē' => 'e', 'ñ' => 'n', 'ń' => 'n', 'ĥ' => 'h', 'ĝ' => 'g', 'đ' => 'd', 'ĵ' => 'j',
            'ÿ' => 'y', 'ũ' => 'u', 'ŭ' => 'u', 'ư' => 'u', 'ţ' => 't', 'ý' => 'y', 'ő' => 'o',
            'â' => 'a', 'ľ' => 'l', 'ẅ' => 'w', 'ż' => 'z', 'ī' => 'i', 'ã' => 'a', 'ġ' => 'g',
            'ṁ' => 'm', 'ō' => 'o', 'ĩ' => 'i', 'ù' => 'u', 'į' => 'i', 'ź' => 'z', 'á' => 'a',
            'û' => 'u', 'þ' => 'th', 'ð' => 'dh', 'æ' => 'ae', 'µ' => 'u', 'ĕ' => 'e', 'ı' => 'i',
            //for vietnamese bat dau tu day
            'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'ḋ' => 'd',
            'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e', 'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o', 'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        );
        $utf8_upper_accents = array(
            'À' => 'A', 'Ô' => 'O', 'Ď' => 'D', 'Ḟ' => 'F', 'Ë' => 'E', 'Š' => 'S', 'Ơ' => 'O',
            'Ă' => 'A', 'Ř' => 'R', 'Ț' => 'T', 'Ň' => 'N', 'Ā' => 'A', 'Ķ' => 'K', 'Ĕ' => 'E',
            'Ŝ' => 'S', 'Ỳ' => 'Y', 'Ņ' => 'N', 'Ĺ' => 'L', 'Ħ' => 'H', 'Ṗ' => 'P', 'Ó' => 'O',
            'Ú' => 'U', 'Ě' => 'E', 'É' => 'E', 'Ç' => 'C', 'Ẁ' => 'W', 'Ċ' => 'C', 'Õ' => 'O',
            'Ṡ' => 'S', 'Ø' => 'O', 'Ģ' => 'G', 'Ŧ' => 'T', 'Ș' => 'S', 'Ė' => 'E', 'Ĉ' => 'C',
            'Ś' => 'S', 'Î' => 'I', 'Ű' => 'U', 'Ć' => 'C', 'Ę' => 'E', 'Ŵ' => 'W', 'Ṫ' => 'T',
            'Ū' => 'U', 'Č' => 'C', 'Ö' => 'O', 'È' => 'E', 'Ŷ' => 'Y', 'Ą' => 'A', 'Ł' => 'L',
            'Ų' => 'U', 'Ů' => 'U', 'Ş' => 'S', 'Ğ' => 'G', 'Ļ' => 'L', 'Ƒ' => 'F', 'Ž' => 'Z',
            'Ẃ' => 'W', 'Ḃ' => 'B', 'Å' => 'A', 'Ì' => 'I', 'Ï' => 'I', 'Ḋ' => 'D', 'Ť' => 'T',
            'Ŗ' => 'R', 'Ä' => 'A', 'Í' => 'I', 'Ŕ' => 'R', 'Ê' => 'E', 'Ü' => 'U', 'Ò' => 'O',
            'Ē' => 'E', 'Ñ' => 'N', 'Ń' => 'N', 'Ĥ' => 'H', 'Ĝ' => 'G', 'Ĵ' => 'J',
            'Ÿ' => 'Y', 'Ũ' => 'U', 'Ŭ' => 'U', 'Ư' => 'U', 'Ţ' => 'T', 'Ý' => 'Y', 'Ő' => 'O',
            'Â' => 'A', 'Ľ' => 'L', 'Ẅ' => 'W', 'Ż' => 'Z', 'Ī' => 'I', 'Ã' => 'A', 'Ġ' => 'G',
            'Ṁ' => 'M', 'Ō' => 'O', 'Ĩ' => 'I', 'Ù' => 'U', 'Į' => 'I', 'Ź' => 'Z', 'Á' => 'A',
            'Û' => 'U', 'Þ' => 'Th', 'Ð' => 'Dh', 'Æ' => 'Ae', 'İ' => 'I',
            //for vietnamese bat dau tu day
            'Á' => 'A', 'À' => 'A', 'Ạ' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ă' => 'A', 'Ắ' => 'A', 'Ằ' => 'A', 'Ặ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Â' => 'A', 'Ấ' => 'A', 'Ầ' => 'A', 'Ậ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A',
            'Đ' => 'D',
            'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E', 'Ê' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O', 'Ô' => 'O', 'Ố' => 'O', 'Ồ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O', 'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U', 'Ư' => 'U', 'Ứ' => 'U', 'Ừ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
            'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y',
        );
        //
        if ((int) $case == 0)
            $utf8_accents = $utf8_lower_accents;
        else
            $utf8_accents = $utf8_upper_accents;
        //
        return str_replace(array_keys($utf8_accents), array_values($utf8_accents), $str);
    }

    public static function stripSymbol($str) {
        return str_replace(array("\r\n", "\n", "\r", "-", "+", "/", "<", ">", ",", "!", ":", ".", "?", "|", "#", "&", "%", "^", "*", ")", "(", "_", "{", "}", "[", "]"), " ", $str);
    }

    /**
     * thangnguyen
     * return alias
     */
    public static function parseToAlias($str) {
        $noMark = HtmlFormat::stripUnicode(trim(mb_strtolower($str, 'UTF-8')));
        $trim_regex = '/[^a-z0-9\-]+/i';
        //
        $alias = str_replace(" ", "-", $noMark);
        $alias = trim(preg_replace($trim_regex, '', $alias));
        return $alias;
    }

    public static function string_cut($string, $length) {
        $des = strip_tags($string);
        $des = str_replace('  ', ' ', $des);
        $sub_fix = substr($des, $length - 1, $length + 31);
        $pos = HtmlFormat::find_str_position(" ", $sub_fix);
        $pos += $length;
        $des = substr($des, 0, $pos);
        return $des;
    }

    public static function find_str_position($find, $string) {
        $pos = strpos($string, $find);
        return $pos;
    }

    /**
     * Nvthaovn
     * Cat chuoi utf-8 ket hop strip_tags
     * @var $str chuoi can cat
     * @var $len dai chuoi sau khi cat (do dai co the tang len 1 vai ky tu neu cat phai giua mot the duoc cho phep)
     * @var $allowable_tags cac the duoc cho phep tuong tu ham strip_tags
     * @var $more neu sau bi cat thi se them chuoi nay vao cuoi default ' ...'
     * @var $encode encode cua chuoi nhap vao
     */
    public static function sub_string($str, $len, $allowable_tags = '', $skip_line = false, $more = '...', $encode = 'utf-8') {
        $allowable_tags = '<br><br/><p>';
        /* Loại bo ca the khong hop le */
        $str = trim(strip_tags($str, $allowable_tags));

        /* Loai bo style cua cac the con lai chuyen the p thanh the br */
        $str = preg_replace('#<\/?p[^>]*>#', '<br/>', $str); // echo $str;die;
        //$str = preg_replace('#<\/?p\s*\w*>#','<br/>',$str);
        /* bỏ các dấu xuống dòng liên tiếp */
        $str = preg_replace('#(<br[^>]*>\s*){2,}#', '<br/>', $str); // bo cac dau xuong dong lien tiep
        /* Bỏ các thẻ rỗng */
        /* bo cac khoang trang canh nhau */
        $str = preg_replace('#\s+#', ' ', $str);

        /* return ngay neu chuỗi đã hợp lệ */
        if ($str == "" || $str == NULL || mb_strlen($str, $encode) <= $len) {
            return $str;
        }
        /* Cắt chuoi theo độ dài đã được yêu cầu */
        $str = mb_substr($str, 0, $len, $encode);
        /* De phong cắt phải giữa thẻ  br */
        $pos = mb_strripos($str, "<", 0, $encode);
        if (($len - $pos) < 5)
            $str = mb_substr($str, 0, $pos, $encode);
        /* nếu cắt phải giữa một từ thì bỏ cả từ đó đi luôn (chỉ thao tác với từ hợp lệ) */
        if ($str != "") {
            $pos = mb_strripos($str, " ", 0, $encode);
            if ($pos !== false && $pos > ($len - 8)) {
                $str = mb_substr($str, 0, $pos + 1, $encode);
            }
            $str .= $more;
        }
        return $str;
    }

    // Thay the cac Url trong noi dung thanh the a
    public static function addAtag($content) {
        $expression1 = "%[^(http:\/\/)(ftp:\/\/)(https:\/\/)](www\.){1,1}(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&\%@!\-\/]))?%";
        $expression = "%((ftp|http|https):\/\/){1,1}(www\.)?(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&\%@!\-\/]))?%";
        $content = preg_replace($expression, " <a href=\"$0\" target=\"_blank\">$0</a> ", $content);
        $content = preg_replace($expression1, " <a href=\"http://$3\" target=\"_blank\">$0</a> ", $content);

        return $content;
    }

    /**
     * Nvthaovn
     * trip tag html van giu cac the xuong dong ( br, p ), bo het cac the rong
     * @var $str : html can cat
     * @var $len : do dai can cat
     * @var $allowable_tags : nhung the khong strip
     * @var $skip_line : giu cac the xuong dong
     * @return String da duoc cat, chỉ con lai the <br/>
     */
    public static function sub_strip($str, $len, $allowable_tags = '', $skip_line = false, $more = '...', $encode = 'utf-8') {
        $allowable_tags = '<br><br/><p>';
        /* Loại bo ca the khong hop le */
        $str = trim(strip_tags($str, $allowable_tags));

        /* Loai bo style cua cac the con lai chuyen the p thanh the br */
        $str = preg_replace('#<\/?p[^>]*>#', '<br/>', $str); // echo $str;die;
        //$str = preg_replace('#<\/?p\s*\w*>#','<br/>',$str);
        /* bỏ các dấu xuống dòng liên tiếp */
        $str = preg_replace('#(<br[^>]*>\s*){2,}#', '<br/>', $str); // bo cac dau xuong dong lien tiep
        /* Bỏ các thẻ rỗng */
        /* bo cac khoang trang canh nhau */
        $str = preg_replace('#\s+#', ' ', $str);

        /* return ngay neu chuỗi đã hợp lệ */
        if ($str == "" || $str == NULL || mb_strlen($str, $encode) <= $len) {
            return $str;
        }
        /* Cắt chuoi theo độ dài đã được yêu cầu */
        $str = mb_substr($str, 0, $len, $encode);
        /* De phong cắt phải giữa thẻ  br */
        $pos = mb_strripos($str, "<", 0, $encode);
        if (($len - $pos) < 5)
            $str = mb_substr($str, 0, $pos, $encode);
        /* nếu cắt phải giữa một từ thì bỏ cả từ đó đi luôn (chỉ thao tác với từ hợp lệ) */
        if ($str != "") {
            $pos = mb_strripos($str, " ", 0, $encode);
            if ($pos !== false && $pos > ($len - 8)) {
                $str = mb_substr($str, 0, $pos + 1, $encode);
            }
            $str .= $more;
        }
        //var_dump($str);die;
        return $str;
    }

    //Lấy số ký tự theo dấu space
    public static function subCharacter($str = null, $delimiter = " ", $number = 10, $offset = 0, $endcharacter = '...') {
        if (!$str)
            return;
        $lenght = mb_strlen($str);
        if ($lenght == 1)
            return $str;
        if ($offset)
            $str = mb_substr($str, $offset, $length - $offset, 'UTF-8');
        $start = 0;
        $count = 0;
        while ($start < $lenght) {
            if ($count >= $number)
                break;
            if (mb_substr($str, $start, 1, 'UTF-8') == $delimiter) {
                $count++;
            }
            $start++;
        }

        if ($start == $lenght)
            return $str;
        else
            return mb_substr($str, 0, $start - 1, 'UTF-8') . $endcharacter;
    }

    /**
     *
     */
    static function money_format($number) {
        $money = number_format($number, 2, ',', '.');
        $money = preg_replace("/,0+[^1-9]/", "", $money);
        return $money;
    }

    static function money_format2($number) {
        $money = number_format($number, is_float($number) ? 2 : 0, ',', '.');
        return $money;
    }

    //Format text
    static function money_format3($number) {
        $money = number_format($number, is_float($number) ? 2 : 0, '.', '.');
        if (count(str_split($money)) > 5) {
            $money2 = str_split(substr($money, 0, -4));
            $html = '';
            if (count($money2)) {
                foreach ($money2 as $key => $value) {
                    $html .= '<span class="' . (($value != '.') ? ('number-' . $value) : 'number-dot') . '"></span>';
                }
            }
            $html .= '<span class="number-dot-d"></span>';
        } else {
            $money2 = str_split($money);
            $html = '';
            if (count($money2)) {
                foreach ($money2 as $key => $value) {
                    $html .= '<span class="' . (($value != '.') ? ('number-' . $value) : 'number-dot') . '"></span>';
                }
            }
        }
        return $html;
    }

}

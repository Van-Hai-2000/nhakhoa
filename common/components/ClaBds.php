<?php

namespace common\components;

use common\components\ClaSite;

/**
 * @description Process string
 *
 * @author hungtm
 */
class ClaBds
{

    const KEY_BOTIENTE_THUE = 1;
    const KEY_BOTIENTE_BAN = 2;

    static function bodonvitiente(){
        return [
            self::KEY_BOTIENTE_THUE => 'Cho thuê', //gắn liền với donvitiente2()
            self::KEY_BOTIENTE_BAN => 'Bán', //gắn liền với donvitiente()
        ];
    }

    static function donvitiente_ban(){
        return [
            '1' => 'Thỏa thuận',
            '2' => 'Triệu',
            '3' => 'Tỷ',
            '4' => 'Trăm nghìn/m2',
            '5' => 'Triệu/m2',
        ];
    }

    static function donvitiente_ban_filter()
    {
        return [
            '1' => ['min' => 0, 'max' => 500],
            '2' => ['min' => 500, 'max' => 800],
            '3' => ['min' => 800, 'max' => 1000],
            '4' => ['min' => 1000, 'max' => 2000],
            '5' => ['min' => 2000, 'max' => 3000],
            '6' => ['min' => 3000, 'max' => 5000],
            '7' => ['min' => 5000, 'max' => 7000],
            '8' => ['min' => 7000, 'max' => 10000],
            '9' => ['min' => 10000, 'max' => 20000],
            '10' => ['min' => 20000, 'max' => 30000],
            '11' => ['min' => 30000, 'max' => ''],
        ];
    }


    static function donvitiente_thue(){
        return [
            '1' => 'Thỏa thuận',
            '2' => 'Trăm nghìn/tháng',
            '3' => 'Triệu/tháng',
            '4' => 'Trăm ngìn/m2/tháng',
            '5' => 'Triệu/m2/tháng',
        ];
    }

    static function donvitiente_thue_fillter(){
        return [
            '1' => ['min' => 0, 'max' => 1],
            '2' => ['min' => 1, 'max' => 3],
            '3' => ['min' => 3, 'max' => 5],
            '4' => ['min' => 5, 'max' => 10],
            '5' => ['min' => 10, 'max' => 40],
            '6' => ['min' => 40, 'max' => 70],
            '7' => ['min' => 70, 'max' => 100],
            '8' => ['min' => 100, 'max' => ''],
        ];
    }

    static function getBoDonVi($type){
        if($type == self::KEY_BOTIENTE_BAN){
            return self::donvitiente_ban();
        }else{
            return self::donvitiente_thue();
        }
    }

    static function getAllBoDonVi(){
        return [
            self::KEY_BOTIENTE_BAN => self::donvitiente_ban(),
            self::KEY_BOTIENTE_THUE => self::donvitiente_thue(),
        ];
    }

    static function huong_nha(){
        return [
            '0' => 'KXĐ',
            '1' => 'Đông',
            '2' => 'Tây',
            '3' => 'Nam',
            '4' => 'Bắc',
            '5' => 'Đông-Bắc',
            '6' => 'Tây-Bắc',
            '7' => 'Tây-Nam',
            '8' => 'Đông-nam',
        ];
    }

    static function convertPriceToMillion($type,$type_id,$value,$dien_tich){
        $money = 0.00;
        if($type == ClaBds::KEY_BOTIENTE_BAN){
            switch ($type_id){
                case 1:
                    $money = 0.00;
                    break;
                case 2:
                    $money = $value;
                    break;
                case 3:
                    $money = $value*1000;
                    break;
                case 4:
                    $money = ($value*$dien_tich)/10;
                    break;
                case 5:
                    $money = $value*$dien_tich;
                    break;
            }
        }else{
            switch ($type_id){
                case 1:
                    $money = '';
                    break;
                case 2:
                    $money = $value/10;
                    break;
                case 3:
                    $money = $value;
                    break;
                case 4:
                    $money = ($value*$dien_tich)/10;
                    break;
                case 5:
                    $money = $value*$dien_tich;
                    break;
            }
        }
        return $money;
    }

    static function dien_tich_fillter(){
        return [
            '1' => ['min' => 0, 'max' => 30],
            '2' => ['min' => 30, 'max' => 50],
            '3' => ['min' => 50, 'max' => 80],
            '4' => ['min' => 80, 'max' => 100],
            '5' => ['min' => 100, 'max' => 150],
            '6' => ['min' => 150, 'max' => 200],
            '7' => ['min' => 200, 'max' => 250],
            '8' => ['min' => 250, 'max' => 300],
            '9' => ['min' => 300, 'max' => ''],
        ];
    }
}

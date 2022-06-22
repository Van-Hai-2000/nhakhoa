<?php

namespace common\components;

use common\models\product\ProductAttributeOption;

class ClaProduct {

    public static function getDataOptionByIds($ids) {
        $data = [];
        if ($ids) {
            $data = ProductAttributeOption::find()
                    ->where('id IN (' . join(',', $ids) . ')')
                    ->asArray()
                    ->all();
        }
        return $data;
    }
    
    public static function optionsPrice() {
        return [
            0 => '0 đ',
            100000 => '100.000 đ',
            200000 => '200.000 đ',
            500000 => '500.000 đ',
            1000000 => '1.000.000 đ',
            2000000 => '2.000.000 đ',
            5000000 => '5.000.000 đ',
            10000000 => '10.000.000 đ',
        ];
    }

}

<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\base\Model;
use common\models\recruitment\Recruitment;
use common\models\news\News;
use common\models\product\Product;
use common\models\news\ContentPage;
use yii\data\Pagination;
use common\components\ClaLid;
/**
 * Login form
 */
class Search extends Model {

    const TYPE_ALL = 0;
    const TYPE_PRO = 1;
    const TYPE_NEW = 2;
    const TYPE_PAGE = 3;
    const TYPE_VIDEO = 4;
    const LIMIT = 12;
    const LOCATION_RANGE_DEFAULT = 10000;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
        ];
    }

    public static function getTypeName($id = 0) {
       $arr = [
        // Search::TYPE_ALL => '--- Tất cả ---',
        // Search::TYPE_PRO => 'Sản phẩm',
        Search::TYPE_NEW => 'Tin tức',
        // Search::TYPE_PAGE => 'Trang nội dung'
        Search::TYPE_VIDEO => 'Video',

       ];

        if($id) {
            return $arr[$id];
        }
        return $arr;
    }

    public static function getTypeNameModel($id) {
        $arr = [
        // Search::TYPE_ALL => '',
        // Search::TYPE_PRO => 'common\models\product\Product',
        Search::TYPE_NEW => 'common\models\news\News',
        // Search::TYPE_PAGE => 'common\models\news\ContentPage',
        Search::TYPE_VIDEO => 'common\models\media\Video',

       ];

        if($id) {
            if(isset($arr[$id])) {
                return $arr[$id];
            }
            return $arr[Search::TYPE_NEW];
        }
        return $arr;
    }

    public static function getData($option) {

        $option['type'] = $option['type'] ? $option['type'] : 1;
        $model = Search::getTypeNameModel($option['type']);
        if($model) {
            $data = $model::getDataSearch($option);
            return $data;
        }
        return null;
    }

     /**
     * find locations that is near the location with ordinate
     * $options: lat, lng, page, $range = km
     */
    static function findNearLocations($options = array()) {
        if (!isset($options['lat']) || !isset($options['lng']))
            return array();
        $condition ='';
        $params ='';
        if(!isset($options['status'])) {
            $condition .= ' AND p.status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
        }
        //

        if(isset($options['wholesale']) && isset($options['retail'])) {
            unset($options['wholesale']);
            unset($options['retail']);
        }
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (p.category_track) AGAINST ('" . $options['category_id'] . "' IN BOOLEAN MODE)";
        }
        if (isset($options['status_quantity'])) {
            $condition .= " AND p.status_quantity = " . $options['status_quantity'];
        }
        if (isset($options['wholesale']) && $options['wholesale']) {
            $condition .= " AND p.price_range IS NOT NULL AND p.price_range <>'' ";
        }
        if (isset($options['retail']) && $options['retail']) {
            $condition .= " AND (p.price_range IS NULL OR p.price_range='') ";
        }
        if (isset($options['rate']) && $options['rate']) {
            $condition .= " AND p.rate >= 4 ";
        }
        if (isset($options['shop_id']) && $options['shop_id']) {
            $condition .= " AND p.shop_id = " . $options['shop_id'];
        }
        if (isset($options['province_id']) && $options['province_id']) {
            $condition .= " AND p.province_id = " . $options['province_id'];
        }
        if (isset($options['price_min']) && $options['price_min']) {
            $condition .= " AND p.price >= " . $options['price_min'];
        }
        if (isset($options['price_max']) && $options['price_max']) {
            $condition .= " AND p.price <= " . $options['price_max'];
        }
        //
        if (isset($options['keyword']) && $options['keyword']) {
            $first_character = substr($options['keyword'], 0, 1);
            if (isset($first_character) && $first_character == '#') {
                $options['keyword'] = ltrim($options['keyword'], '#');
                $condition .= ' AND p.id=:id';
                $params[':id'] = $options['keyword'];
            } else {
                $condition .= ' AND p.name LIKE :name';
                $params[':name'] = '%' . $options['keyword'] . '%';
            }
        }
        //
        if (isset($options['brand']) && $options['brand']) {
            $condition .= ' AND p.brand=:brand';
            $params[':brand'] = $options['brand'];
        }
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND p.ishot=:ishot';
            $params[':ishot'] = $options['ishot'];
        }
        //
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        if (isset($options['page'])) {
            $offset = ($options['page'] - 1) * $limit;
        } else {
            $offset = 0;
        }
        $order = Product::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }
        if (isset($options['level']) && $options['level']) {
            $condition .= ' AND r.level=:level';
            $params[':level'] = $options['level'];
        }
        if (isset($options['id']) && $options['id'] && is_array($options['id'])) {
            $ids_strings = implode(',', $options['id']);
            $condition .= " AND p.id IN ($ids_strings)";
        }
        //
        
        $lat = doubleval($options['lat']);
        $lng = doubleval($options['lng']);
        $range = isset($options['range']) ? doubleval($options['range']) : self::LOCATION_RANGE_DEFAULT;
        if (isset($options['location_type']) && (int) $options['location_type']) {
            $condition.= ' AND location_type=:location_type';
            $params[':location_type'] = $options['location_type'];
        }
        if (isset($options['name']) && $options['name'] != '') {
            $name = trim($options['name']);
            if ($name != '') {
                //$name = '%' . str_replace(' ', '%', $name) . '%';
                //$condition.= ' AND name like \'' . $name . '\'';
                $name = '*' . str_replace(array("'", ' '), array("\'", '*'), $name) . '*';
                $condition.=" AND MATCH (p.name) AGAINST ('$name' IN NATURAL LANGUAGE MODE)";
                //$params[':keyword'] = $name;
                //$range = $range * 8;
            }
        }
        //
        $limit = isset($options['limit']) ? (int) $options['limit'] : self::LIMIT;
        $offset = (isset($options['page']) ? ( (int) $options['page'] - 1) : 0) * $limit;
        //
        $dbcommand = (new Query())->select("p.*,(ACOS(SIN(RADIANS(s.lat))*SIN(RADIANS($lat)) + COS(RADIANS(s.lat))*COS(RADIANS($lat))*COS(RADIANS(s.lng-$lng)))*6370)*1000 as distance, s.latlng")
                    ->from('shop s')
                    ->leftJoin('product p', 's.id = p.shop_id')
                    ->where("(ACOS(SIN(RADIANS(s.lat))*SIN(RADIANS($lat)) + COS(RADIANS(s.lat))*COS(RADIANS($lat))*COS(RADIANS(s.lng-$lng)))*6370)*1000 <= " . $range . $condition, $params);
        // Nếu mà không phải fultext search thì sẽ dùng order (fultext search dạng natural đã có rank cho search rồi)
        $dbcommand->orderBy('distance ASC');
        if (isset($options['count'])) {
            return $dbcommand->count();
        }
        $data = $dbcommand
                // ->limit($limit)
                // ->offset($offset)
                ->all();
        return $data;
    }
}

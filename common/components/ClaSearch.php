<?php

namespace common\components;

// use yii\helpers\Url; 
use Yii;
use yii\db\Query;
use yii\base\Model;
use common\models\product\Product;
use common\models\shop\Shop;
use common\components\ClaLid;
/*
 * Class for create and show menu
 */

class ClaSearch
{

    const TYPE_PRODUCT = 1;
    const TYPE_SHOP = 2;
    // const TYPE_NEW = 2;
    // const TYPE_PAGE = 3;
    const LIMIT = 12;
    const LOCATION_RANGE_DEFAULT = 20000000000;

    public static function optionType($id = null)
    {
        $types = [
            self::TYPE_PRODUCT => Yii::t('app', 'product'),
            self::TYPE_SHOP => Yii::t('app', 'shop'),
        ];
        if (isset($types[$id])) {
            return $types[$id];
        }
        return $types;
    }

    public static function optionlinkType($id = null, $data = [])
    {
        $types = [
            self::TYPE_PRODUCT => '/search/search/index',
            self::TYPE_SHOP => '/search/search/shop',
        ];
        if (isset($types[$id])) {
            return $types[$id];
        }
        return $types;
    }

    public static function addSearch($data)
    {
        \Yii::$app->session->open();
        if (isset($data['keyword'])) {
            $_SESSION['ToolSearch'][$data['keyword']] = $data;
            return 1;
        }
        return 0;
    }

    public static function getSearch()
    {
        $data = [];
        if (isset($_SESSION['ToolSearch']) && $_SESSION['ToolSearch']) {
            foreach ($_SESSION['ToolSearch'] as $value) {
                $data[] = $value;
            }
            return $data;
        }
        if (!$data) {
            $data = \common\models\product\ProductTopsearch::getTopsearch([
                'limit' => 10
            ]);
        }
        return $data;
    }

    public static function getTypeNameModel($id)
    {
        $arr = [
            Search::TYPE_ALL => '',
            Search::TYPE_PRO => 'common\models\product\Product',
            Search::TYPE_NEW => 'common\models\news\News',
            Search::TYPE_PAGE => 'common\models\news\ContentPage'
        ];

        if ($id) {
            if (isset($arr[$id])) {
                return $arr[$id];
            }
            return $arr[Search::TYPE_NEW];
        }
        return $arr;
    }

    public static function getData($option)
    {

        $option['type'] = $option['type'] ? $option['type'] : 1;
        $model = Search::getTypeNameModel($option['type']);
        if ($model) {
            $data = $model::getDataSearch($option);
            return $data;
        }
        return null;
    }

    /**
     * find locations that is near the location with ordinate
     * $options: lat, lng, page, $range = km
     */
    static function findNearLocations($options = array())
    {
        if (!isset($options['lat']) || !isset($options['lng']))
            return array();
        $condition = '1 = 1 ';
        $params = '';
        if (!isset($options['status'])) {
            $condition .= ' AND p.status=:status';
            $params = [':status' => ClaLid::STATUS_ACTIVED,];
        }
        // if (isset($options['wholesale']) && isset($options['retail'])) {
        //     unset($options['wholesale']);
        //     unset($options['retail']);
        // }
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (t.category_track) AGAINST ('" . __removeDF($options['category_id']) . "' IN BOOLEAN MODE)";
        }
        if (isset($options['status_quantity'])) {
            $condition .= " AND t.status_quantity = :status_quantity";
            $params[':status_quantity'] = $options['status_quantity'];
        }
        if (isset($options['wholesale']) && $options['wholesale']) {
            $condition .= " AND t.price_range IS NOT NULL AND t.price_range <>'' ";
        }
        if (isset($options['retail']) && $options['retail']) {
            $condition .= " AND (t.price_range IS NULL OR t.price_range='') ";
        }
        if (isset($options['rate']) && $options['rate']) {
            $condition .= " AND t.rate >= 4 ";
        }
        if (isset($options['shop_id']) && $options['shop_id']) {
            $condition .= " AND t.shop_id = :shop_id";
            $params[':shop_id'] = $options['shop_id'];
        }
        if (isset($options['province_id']) && $options['province_id']) {
            $condition .= " AND t.province_id = :province_id";
            $params[':province_id'] = $options['province_id'];
        }
        if (isset($options['status_affiliate']) && $options['status_affiliate']) {
            $condition .= " AND t.status_affiliate = :status_affiliate";
            $params[':status_affiliate'] = $options['status_affiliate'];
        }
        if (isset($options['price_min']) && $options['price_min']) {
            $condition .= " AND t.price >= :price_min";
            $params[':price_min'] = $options['price_min'];
        }
        if (isset($options['price_max']) && $options['price_max']) {
            $condition .= " AND t.price <= :price_max";
            $params[':price_max'] = $options['price_max'];
        }
        if (isset($options['brand']) && $options['brand']) {
            $condition .= ' AND t.brand=:brand';
            $params[':brand'] = $options['brand'];
        }
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND t.ishot=:ishot';
            $params[':ishot'] = $options['ishot'];
        }

        if (isset($options['isnew']) && $options['isnew']) {
            $condition .= ' AND t.isnew=:isnew';
            $params[':isnew'] = $options['isnew'];
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
        // $order = Product::DEFAULT_ORDER;
        // if (isset($options['order']) && $options['order']) {
        //     $order = __removeDF($options['order']);
        // }
        if (isset($options['level']) && $options['level']) {
            $condition .= ' AND r.level=:level';
            $params[':level'] = $options['level'];
        }
        if (isset($options['id']) && $options['id'] && is_array($options['id'])) {
            $ids_strings = __removeDF(implode(',', $options['id']));
            $condition .= " AND p.id IN ($ids_strings)";
        }
        //

        $lat = doubleval($options['lat']);
        $lng = doubleval($options['lng']);
        $range = isset($options['range']) ? doubleval($options['range']) : self::LOCATION_RANGE_DEFAULT;
        if (isset($options['location_type']) && (int) $options['location_type']) {
            $condition .= ' AND location_type=:location_type';
            $params[':location_type'] = $options['location_type'];
        }

        $select = "p.*,(ACOS(SIN(RADIANS(s.lat))*SIN(RADIANS($lat)) + COS(RADIANS(s.lat))*COS(RADIANS($lat))*COS(RADIANS(s.lng" . ($lng > 0 ? '-' . $lng : ' + ' . (-$lng)) . ")))*6370)*1000 as distance, s.latlng";

        $dbcommand = (new Query())->select($select)
            ->from('shop s')
            ->leftJoin('product p', 's.id = p.shop_id')
            ->where("(ACOS(SIN(RADIANS(s.lat))*SIN(RADIANS($lat)) + COS(RADIANS(s.lat))*COS(RADIANS($lat))*COS(RADIANS(s.lng" . ($lng > 0 ? '-' . $lng : ' + ' . (-$lng)) . ")))*6370)*1000 <= " . $range . $condition, $params);
        // Nếu mà không phải fultext search thì sẽ dùng order (fultext search dạng natural đã có rank cho search rồi)
        $order = "distance ASC";
        if (isset($options['keyword']) && $options['keyword']) {
            $tag = $options['keyword'];
            $tag = str_replace(',', ' ', $tag);
            if ($tag) {
                $dbcommand->select($select . ", ((1.8 * (MATCH(p.name) AGAINST (:tag IN BOOLEAN MODE)))) as score ");
                $dbcommand->andWhere("(MATCH(p.name) AGAINST (:tag IN BOOLEAN MODE))", [':tag' => $tag]);
                $dbcommand->having('score > 1');
                $order = 'score DESC, ' . $order;
            }
        }
        if (isset($options['count'])) {
            return $dbcommand->count();
        }
        $data = $dbcommand
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();
        return $data;
    }

    static function findNearLocationsShop($options = array())
    {
        if (!isset($options['lat']) || !isset($options['lng']))
            return array();
        $condition = '1 = 1 ';
        $params = '';
        if (!isset($options['status'])) {
            $condition .= ' AND s.status=:status';
            $params = [':status' => ClaLid::STATUS_ACTIVED];
        }
        //
        if (isset($options['rate']) && $options['rate']) {
            $condition .= " AND s.rate >= 4 ";
        }
        if (isset($options['shop_id']) && $options['shop_id']) {
            $condition .= " AND s.id = :shop_id";
            $params = [':shop_id' => $options['shop_id']];
        }
        //

        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit'] >= 1) {
            $limit = $options['limit'];
        }
        //
        if (isset($options['page'])) {
            $offset = ($options['page'] - 1) * $limit;
        } else {
            $offset = 0;
        }
        // $order = Shop::DEFAULT_ORDER;
        // if (isset($options['order']) && $options['order']) {
        //     $order = __removeDF($options['order']);
        // }
        if (isset($options['level']) && $options['level']) {
            $condition .= ' AND s.level=:level';
            $params[':level'] = $options['level'];
        }
        if (isset($options['id']) && $options['id'] && is_array($options['id'])) {
            $ids_strings = __removeDF(implode(',', $options['id']));
            $condition .= " AND s.id IN ($ids_strings)";
        }
        if (isset($options['keyword']) && $options['keyword']) {
            $condition .= " AND s.name LIKE :keyword";
            $params[':keyword'] = '%' . $options['keyword'] . '%';
        }
        $lat = doubleval($options['lat']);
        $lng = doubleval($options['lng']);
        $range = isset($options['range']) ? doubleval($options['range']) : self::LOCATION_RANGE_DEFAULT;
        if (isset($options['location_type']) && (int) $options['location_type']) {
            $condition .= ' AND location_type=:location_type';
            $params[':location_type'] = $options['location_type'];
        }

        $select = "s.*, (ACOS(SIN(RADIANS(s.lat))*SIN(RADIANS($lat)) + COS(RADIANS(s.lat))*COS(RADIANS($lat))*COS(RADIANS(s.lng" . ($lng > 0 ? '-' . $lng : ' + ' . (-$lng)) . ")))*6370)*1000 as distance";
        $dbcommand = (new Query())->select($select)
            ->from('shop s')
            ->where("(ACOS(SIN(RADIANS(s.lat))*SIN(RADIANS($lat)) + COS(RADIANS(s.lat))*COS(RADIANS($lat))*COS(RADIANS(s.lng" . ($lng > 0 ? '-' . $lng : ' + ' . (-$lng)) . ")))*6370)*1000 <= " . $range . $condition, $params);
        // Nếu mà không phải fultext search thì sẽ dùng order (fultext search dạng natural đã có rank cho search rồi)
        $order = "distance ASC";
        if (isset($options['count'])) {
            return $dbcommand->count();
        }
        $data = $dbcommand
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();
        return $data;
    }
}

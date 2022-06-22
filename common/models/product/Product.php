<?php

namespace common\models\product;

use common\components\ClaActiveRecordLog;
use Yii;
use yii\db\Query;
use common\components\ClaLid;
use common\models\rating\Rating;

/**
 * This is the model class for table "product".
 *
 * @property string $id
 * @property integer $brand
 * @property string $name
 * @property string $alias
 * @property string $category_id
 * @property string $category_track
 * @property string $code
 * @property string $barcode
 * @property string $price
 * @property string $price_market
 * @property integer $currency
 * @property string $quantity
 * @property integer $status
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $avatar_id
 * @property integer $ishot
 * @property integer $price_market_af
 * @property string $viewed
 * @property string $created_at
 * @property string $updated_at
 * @property string $short_description
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $dynamic_field
 * @property string $order
 * @property string $price_loaimau
 * @property string $loaimau_id
 */
class Product extends ClaActiveRecordLog
{

    const PRODUCT_VIEWED = 'product_viewed';
    const DEFAULT_ORDER = 'id DESC';
    const PRODUCT_SEARCH = 'product_search';
    public $avatar;
    public $_provinces;
    public $_shops;
    public $_wishs;
    public $_price = null;
    public $_price_market = null;
    public $_price_market_text = null;
    public $price_af = null;
    public $price_market_af = null;
    public $price_text_af = null;
    public $_price_text = null;
    public $_merrors = null;
    public $shop_status_affiliate = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['code', 'unique', 'targetClass' => '\common\models\product\Product', 'message' => 'Sản phẩm đã tồn tại.'],
            [['brand', 'category_id', 'quantity', 'status', 'ishot', 'viewed', 'created_at', 'updated_at', 'avatar_id', 'currency', 'order', 'number_time', 'status_quantity', 'fee_ship', 'voso_connect', 'affiliate_gt_product', 'affiliate_m_v', 'affiliate_m_ov', 'affiliate_safe', 'isnew','loaimau_id'], 'integer'],
            [['name', 'category_id'], 'required'],
            [['name', 'alias', 'barcode', 'avatar_path', 'avatar_name', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['category_track', 'code'], 'string', 'max' => 68],
            [['short_description', 'description', 'dynamic_field', 'flash_sale', 'quality_range', 'price_range', 'price','price_loaimau', 'price_market', 'avatar', 'unit', 'weight', 'height', 'length', 'width', 'shop_id', 'type', 'start_time', 'number_time', 'status_quantity', 'note_fee_ship', 'videos', 'ckedit_desc', 'status_affiliate', 'shop_status_affiliate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand' => 'Thương hiệu',
            'name' => 'Tên thủ thuật',
            'alias' => 'Alias',
            'category_id' => 'Nhóm thủ thuật',
            'category_track' => 'Category Track',
            'code' => 'Mã sản phẩm',
            'barcode' => 'Mã vạch',
            'price' => 'Giá',
            'price_market' => 'Giá chưa giảm',
            'loaimau_id' => 'Loại mẫu',
            'currency' => 'Loại tiền tệ',
            'quantity' => 'Số lượng',
            'status' => 'Trạng thái',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'ishot' => 'Nổi bật',
            'isnew' => 'SP HOT',
            'viewed' => 'Lượt xem',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả chi tiết',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'order' => 'Số thứ tự',
            'flash_sale' => 'Giảm giá sốc',
            'unit' => 'Đơn vị tính',
            'avatar' => 'Ảnh đại diện',
            'weight' => Yii::t('app', 'weights'),
            'type' => Yii::t('app', 'type_product'),
            'start_time' => Yii::t('app', 'start_time_product'),
            'number_time' => Yii::t('app', 'number_time_product'),
            'note_fee_ship' => 'Chi tiết miễn phí nội thành',
            'fee_ship' => 'Miễn phí nội thành',
            'ckedit_desc' => 'Sử dụng trình soạn thảo',
            'affiliate_gt_product' => 'Người giới thiệu sản phẩm',
            'affiliate_m_v' => 'Tài khoản mua bằng V',
            'affiliate_m_ov' => 'Tài khoản mua bằng hình thức khác V',
            'affiliate_safe' => 'Mọi tài khoản mua',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            return true;
        } else {
            return false;
        }
    }

    function checkAffiliate()
    {
        if ($this->status_affiliate == 1) {
            $shop = \common\models\shop\Shop::find()->where("id = " . $this->shop_id)->one();
            $sum = $shop->getSumAffCheck();
            if (($sum + $this->affiliate_gt_product + $this->affiliate_m_v + $this->affiliate_safe + $this->affiliate_charity) <= 100) {
                return true;
            }
            $this->addError('status_affiliate', 'Tổng % affiliate vượt quá 100% vui lòng kiểm tra lại.');
            return false;
        }
        return true;
    }

    public static function getPriceRange()
    {
        return [
            '0' => ['min' => 0, 'max' => '50000'],
            '1' => ['min' => 50000, 'max' => '100000'],
            '2' => ['min' => 100000, 'max' => '500000'],
            '3' => ['min' => 500000, 'max' => '1000000'],
            '4' => ['min' => 1000000, 'max' => '5000000'],
            '5' => ['min' => 5000000, 'max' => '10000000'],
        ];
    }

    public function getType($id = 0)
    {
        $data =  [
            '1' => Yii::t('app', 'normal'),
            '2' => Yii::t('app', 'dont_have_here'),
            // '3' => Yii::t('app', 'dont_have_now'),
        ];
        return isset($data[$id]) ? $data[$id] : $data;
    }

    public static function arrayBrand()
    {
        return Brand::getListBrand();
    }

    public static function getBrandName($id)
    {
        $data = self::arrayBrand();
        return isset($data[$id]) && $data[$id] ? $data[$id] : '';
    }

    public static function getImages($id)
    {
        $result = [];
        if (!$id) {
            return $result;
        }
        $result = (new \yii\db\Query())->select('*')
            ->from('product_image')
            ->where('product_id=:product_id', [':product_id' => $id])
            ->orderBy('order ASC, created_at DESC')
            ->all();
        return $result;
    }

    public static function getConfigurables($id)
    {
        $result = [];
        if (!$id) {
            return $result;
        }
        $result = (new \yii\db\Query())->select('*')
            ->from('product_configurable')
            ->where('product_id=:product_id', [':product_id' => $id])
            ->all();
        return $result;
    }

    /**
     *
     * @return type
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'category_id']);
    }

    public function getCategories()
    {
        if ($this->list_category && ($list = explode(' ', $this->list_category)) > 2) {
            $string = [];
            foreach ($list as $id) {
                $string[] = ($cat = ProductCategory::findOne($id)) ? $cat->name : $id;
            }
            return implode('<br/>', $string);
        }
        return $this->category->name;
    }

    /**
     * @hungtm
     * return array options products
     */
    public static function optionsProduct()
    {
        $data = (new Query())->select('*')
            ->from('product')
            ->where('status=:status', [':status' => ClaLid::STATUS_ACTIVED])
            ->orderBy('id DESC')
            ->all();
        return array_column($data, 'name', 'id');
    }

    /**
     * get product by ids
     * @param type $ids
     */
    public static function getProductsByIds($ids, $_product_id = 0)
    {
        $ids_string = implode(',', $ids);
        //
        $condition = 'status=:status AND id IN (' . $ids_string . ')';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        if ($_product_id) {
            $condition .= ' AND id <> :_product_id';
            $params[':_product_id'] = $_product_id;
        }
        //
        $data = (new Query())->select('*')
            ->from('product')
            ->where($condition, $params)
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

    public static function getProductsByCode($code)
    {
        $data = (new Query())->select('*')
            ->from('product')
            ->where('status=:status AND code=:code', [':status' => ClaLid::STATUS_ACTIVED, ':code' => $code])
            ->orderBy('id DESC')
            ->one();
        return $data;
    }

    public static function getProductsByBrand($id, $options = [])
    {
        $condition = 'status=:status AND brand=:brand';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED,
            ':brand' => $id
        ];
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }

        $data = (new Query())->select('*')
            ->from('product')
            ->where($condition, $params)
            ->orderBy('id DESC')
            ->limit($limit)
            ->all();
        return $data;
    }

    /**
     * @param array $options
     * @return array
     */

    public static function getBds($options = array())
    {
        if(isset($options['user_id']) && $options['user_id']){
            $query = "SELECT product.*, province.name as province_name , district.name as district_name, ward.name as ward_name, product_wish.id as wish_id FROM product LEFT JOIN product_wish ON product_wish.product_id = product.id AND product_wish.user_id = ".$options['user_id']."  LEFT JOIN province ON product.province_id = province.id LEFT JOIN district ON product.district_id = district.id LEFT JOIN ward ON product.ward_id = ward.id WHERE status=".ClaLid::STATUS_ACTIVED;
        }else{
            $query = "SELECT product.*, province.name as province_name , district.name as district_name, ward.name as ward_name, product_wish.id as wish_id FROM product LEFT JOIN product_wish ON product_wish.product_id = product.id  LEFT JOIN province ON product.province_id = province.id LEFT JOIN district ON product.district_id = district.id LEFT JOIN ward ON product.ward_id = ward.id WHERE status=".ClaLid::STATUS_ACTIVED;
        }

        //tìm kiếm theo loại hình mua bán, cho thuê
        if (isset($options['hinh_thuc']) && $options['hinh_thuc']) {
            $query .= ' AND category_type_id = ' . $options['hinh_thuc'] . '';
        }

        //tìm kiếm theo danh mục
        if (isset($options['category_id']) && $options['category_id']) {
            $query .= ' AND product.category_id = ' . $options['category_id'] . '';
        }

        //Tìm kiếm theo địa điểm
        if (isset($options['ward_id']) && $options['ward_id']) {
            $query .= ' AND product.ward_id = ' . $options['ward_id'] . '';
        }elseif (isset($options['district_id']) && $options['district_id']) {
            $query .= ' AND product.district_id = ' . $options['district_id'] . '';
        }elseif (isset($options['province_id']) && $options['province_id']) {
            $query .= ' AND product.province_id = ' . $options['province_id'] . '';
        }

        //tìm kiếm theo giá
        if (isset($options['price_min']) && $options['price_min']) {
            $query .= ' AND product.price_to_million >= ' .$options['price_min'].'';
        }
        if (isset($options['price_max']) && $options['price_max']) {
            $query .= ' AND product.price_to_million <= ' .$options['price_max'].'';
        }

        //Tìm kiếm theo diện tích
        if (isset($options['dien_tich_min']) && $options['dien_tich_min']) {
            $query .= ' AND product.dien_tich >= ' .$options['dien_tich_min'].'';
        }
        if (isset($options['dien_tich_max']) && $options['dien_tich_max']) {
            $query .= ' AND product.dien_tich <= ' .$options['dien_tich_max'].'';
        }

        //tìm kiếm theo dự án
        if (isset($options['project']) && $options['project']) {
            $query .= ' AND product.project_id = ' . $options['project'] . '';
        }

        //tìm kiếm theo số phóng
        if (isset($options['so_phong']) && $options['so_phong']) {
            $query .= ' AND product.so_phong_ngu >= ' . $options['so_phong'] . '';
        }

        //tìm kiếm theo hướng nhà
        if (isset($options['huong_nha']) && $options['huong_nha']) {
            $query .= ' AND product.huong_nha = ' . $options['huong_nha'] . '';
        }

        //tìm kiếm theo key
        if (isset($options['s']) && $options['s']) {
            $query .= " AND MATCH (product.name) AGAINST ('".$options['s']."' IN BOOLEAN MODE)";
        }

        $query .= ' ORDER BY product.created_at DESC';
        $count = Yii::$app->db->createCommand($query)->execute();
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
            $query .= ' limit ' . $limit . '';
        }
        if (isset($options['page']) && $options['page']) {
            $page = $options['page'];
            $query .= ' offset ' . ($page - 1) * $limit . ' ';
        }
        $data = Yii::$app->db->createCommand($query)->queryAll();
        return [
            'data' => $data,
            'totalItem' => $count
        ];
    }


    public static function getProduct($options = [])
    {
        $condition = '1 = 1';
        $params = [];
        if (!isset($options['status'])) {
            $condition = 't.status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
            $condition .= ' AND r.status=:status';
            $condition .= ' AND pc.status=:status';
        }

        if (isset($options['wholesale']) && isset($options['retail'])) {
            unset($options['wholesale']);
            unset($options['retail']);
        }
        if (isset($options['category_id']) && $options['category_id']) {
            $cats = is_array($options['category_id']) ? implode(' ', $options['category_id']) : $options['category_id'];
            $condition .= " AND MATCH (t.category_track) AGAINST ('" . __removeDF($cats) . "' IN BOOLEAN MODE)";
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
        if (isset($options['shop_status_affiliate']) && $options['shop_status_affiliate']) {
            $condition .= ' AND r.status_affiliate=:shop_status_affiliate';
            $params[':shop_status_affiliate'] = $options['shop_status_affiliate'];
        }

        if (isset($options['status_affiliate']) && $options['status_affiliate']) {
            $condition .= ' AND t.status_affiliate=:status_affiliate';
            $params[':status_affiliate'] = $options['status_affiliate'];
        }

        //
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) &&  $options['limit'] >= 1) {
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
            $order = __removeDF($options['order']);
        }
        if (isset($options['level']) && $options['level']) {
            $condition .= ' AND r.level=:level';
            $params[':level'] = $options['level'];
        }
        if (isset($options['type']) && $options['type']) {
            $condition .= ' AND r.type=:type';
            $params[':type'] = $options['type'];
        }
        if (isset($options['id']) && $options['id'] && is_array($options['id'])) {
            $ids_strings = implode(',', __removeDF($options['id']));
            $condition .= " AND t.id IN ($ids_strings)";
        }

        if (isset($options['_id']) && $options['_id']) {
            if (is_array($options['_id'])) {
                $ids_strings = __removeDF(implode(',', $options['_id']));
                $condition .= " AND t.id NOT IN ($ids_strings)";
            } else {
                $ids_strings = __removeDF($options['_id']);
                $condition .= " AND t.id <> $ids_strings";
            }
        }
        $select = 't.*, r.status_affiliate as shop_status_affiliate, r.latlng';
        $query =  (new Query())->select($select)
            ->from('product AS t')
            ->rightJoin('shop AS r', 'r.id = t.shop_id')
            ->rightJoin('product_category pc', "pc.id = t.category_id")
            ->where($condition, $params);

        if (isset($options['keyword']) && $options['keyword']) {
            $tag = $options['keyword'];
            $tag = str_replace(',', ' ', $tag);
            if ($tag) {
                $query->select($select . ", ((1.8 * (MATCH(t.name) AGAINST (:tag IN BOOLEAN MODE)))) as score ");
                $query->andWhere("(MATCH(t.name) AGAINST (:tag IN BOOLEAN MODE))", [':tag' => $tag]);
                $query->having('score > 1');
                $order = 'score DESC, ' . $order;
            }
        }

        if (isset($options['count'])) {
            $total = $query->count();
            return $total;
        }
        $data = $query->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();

        return $data;
    }


    public static function getProductInCate($options = []) {
        $condition = '1 = 1';
        $params = [];
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (t.category_track) AGAINST ('" .$options['category_id'] . "' IN BOOLEAN MODE)";
        }
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) &&  $options['limit'] >= 1) {
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
            $order = __removeDF($options['order']);
        }
        $select = 't.*';
        $query =  (new Query())->select($select)
            ->from('product AS t')
            ->where($condition, $params);
        if (isset($options['count'])) {
            $total = $query->count();
            return $total;
        }
        $data = $query->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();

        return $data;
    }

    public static function getProductSearchTD($options = [])
    {

        $condition = '1 = 1';

        $params = '';
        if (!isset($options['status'])) {
            $condition = 't.status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
        }

        if (isset($options['wholesale']) && isset($options['retail'])) {
            unset($options['wholesale']);
            unset($options['retail']);
        }
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (t.category_track) AGAINST ('" . $options['category_id'] . "' IN BOOLEAN MODE)";
        }
        if (isset($options['status_quantity'])) {
            $condition .= " AND t.status_quantity = " . $options['status_quantity'];
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
            $condition .= " AND t.shop_id = " . $options['shop_id'];
        }
        if (isset($options['province_id']) && $options['province_id']) {
            $condition .= " AND t.province_id = " . $options['province_id'];
        }

        if (isset($options['price_min']) && $options['price_min']) {
            $condition .= " AND t.price >= " . $options['price_min'];
        }
        if (isset($options['price_max']) && $options['price_max']) {
            $condition .= " AND t.price <= " . $options['price_max'];
        }
        //
        if (isset($options['keyword']) && $options['keyword']) {
            $first_character = substr($options['keyword'], 0, 1);
            if (isset($first_character) && $first_character == '#') {
                $options['keyword'] = ltrim($options['keyword'], '#');
                $condition .= ' AND t.id=:id';
                $params[':id'] = $options['keyword'];
            } else {
                $key_change = self::changeKey($options['keyword']);
                $key_change = self::changeKeyOpen($key_change);
                if (is_array($key_change) && $key_change) {
                    $condition .= ' AND (1=0 ';
                    foreach ($key_change as $key => $value) {
                        $condition .= " OR t.alias LIKE '%$value%'";
                    }
                    $condition .= ')';
                    // echo $condition; die(); 
                } else {
                    $condition .= ' AND t.alias LIKE :name';
                    $params[':name'] = '%' . $key_change . '%';
                }
            }
        }
        //
        if (isset($options['brand']) && $options['brand']) {
            $condition .= ' AND t.brand=:brand';
            $params[':brand'] = $options['brand'];
        }
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND t.ishot=:ishot';
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
        if (isset($options['type']) && $options['type']) {
            $condition .= ' AND r.type=:type';
            $params[':type'] = $options['type'];
        }
        if (isset($options['id']) && $options['id'] && is_array($options['id'])) {
            $ids_strings = implode(',', $options['id']);
            $condition .= " AND t.id IN ($ids_strings)";
        }

        //
        if (isset($options['count'])) {
            $total = (new Query())->select('t.*')
                ->from('product AS t')
                ->rightJoin('shop AS r', 'r.id = t.shop_id')
                ->where($condition, $params)
                ->count();
            return $total;
        }
        $data = (new Query())->select('t.*, r.latlng')
            ->from('product AS t')
            ->rightJoin('shop AS r', 'r.id = t.shop_id')
            ->where($condition, $params)
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();

        return $data;
    }

    public static function changeKey($string)
    {
        return $string = \common\components\HtmlFormat::parseToAlias($string);
    }

    public static function changeKeyOpen($string)
    {
        $string = explode('-', $string);
        $list = $string;
        if (count($string) > 2) {
            $list = [];
            for ($i = 0; $i < (count($string)); $i = $i + 2) {
                if (isset($string[$i + 1])) {
                    $list = $string[$i] . '-' . $string[$i + 1];
                }
            }
        }
        return $list;
    }

    public static function getProductPromotion($options = [])
    {

        $condition = '1 = 1';
        $params = '';
        $promotion_id = $options['promotion_id'];
        // $promotion = \common\models\promotion\Promotions::findOne($promotion_id);
        // $hour =  ($options['hour'] == '') ? $promotion->getHourNow() : $options['hour'];
        // $hour_before =  $promotion->getHourBefore($hour);
        $_product_id  = \common\models\promotion\ProductToPromotions::getProductByAttr([
            'attr' => [
                't.id' => $options['promotion_id'],
                // 'hour_space_start' => [$hour,$hour_before],
            ],
            'order' => 'id desc',
            'limit' => '100000'
        ]);
        $_product_id = count($_product_id) ? array_column($_product_id, 'id') : '';
        if (!isset($options['status'])) {
            $condition = 't.status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
        }
        if ($_product_id) {
            $condition .= ' AND t.id NOT IN(' . implode(',', $_product_id) . ')';
        }
        $condition .= " AND (t.price > 0 OR (t.price_range IS NOT NULL AND t.price_range <> ''))";
        //
        if (isset($options['keyword']) && $options['keyword']) {
            $first_character = substr($options['keyword'], 0, 1);
            if (isset($first_character) && $first_character == '#') {
                $options['keyword'] = ltrim($options['keyword'], '#');
                $condition .= ' AND t.id=:id';
                $params[':id'] = $options['keyword'];
            } else {
                $condition .= ' AND t.name LIKE :name';
                $params[':name'] = '%' . $options['keyword'] . '%';
            }
        }
        $condition .= " AND t.status_quantity = 1";
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
        //
        if (isset($options['count'])) {
            $total = (new Query())->select('t.*')
                ->from('product AS t')
                ->where($condition, $params)
                ->count();
            return $total;
        }
        $data = (new Query())->select('t.*')
            ->from('product AS t')
            ->where($condition, $params)
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();

        return $data;
    }

    public static function getProductPromotionShop($options = [])
    {

        $condition = '1 = 1';
        $params = '';
        $promotion_id = $options['promotion_id'];
        // $promotion = \common\models\promotion\Promotions::findOne($promotion_id);
        // $hour =  ($options['hour'] == '') ? $promotion->getHourNow() : $options['hour'];
        // $hour_before =  $promotion->getHourBefore($hour);
        $_product_id  = \common\models\promotion\ProductToPromotions::getProductByAttr([
            'attr' => [
                't.id' => $options['promotion_id'],
                // 'hour_space_start' => [$hour,$hour_before],
            ],
            'order' => 'id desc',
            'limit' => '100000'
        ]);
        $_product_id = array_column($_product_id, 'id');
        if (!isset($options['status'])) {
            $condition = 't.status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
        }
        if ($_product_id) {
            $condition .= ' AND t.id NOT IN(' . implode(',', $_product_id) . ')';
        }
        $condition .= " AND (t.price > 0 OR (t.price_range IS NOT NULL AND t.price_range <> ''))";
        $condition .= " AND t.shop_id = " . Yii::$app->user->id;
        $condition .= " AND t.status_quantity = 1";
        //
        if (isset($options['keyword']) && $options['keyword']) {
            $first_character = substr($options['keyword'], 0, 1);
            if (isset($first_character) && $first_character == '#') {
                $options['keyword'] = ltrim($options['keyword'], '#');
                $condition .= ' AND t.id=:id';
                $params[':id'] = $options['keyword'];
            } else {
                $condition .= ' AND t.name LIKE :name';
                $params[':name'] = '%' . $options['keyword'] . '%';
            }
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
        //
        if (isset($options['count'])) {
            $total = (new Query())->select('t.*')
                ->from('product AS t')
                ->where($condition, $params)
                ->count();
            return $total;
        }
        $data = (new Query())->select('t.*')
            ->from('product AS t')
            ->where($condition, $params)
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();

        return $data;
    }

    public static function countAllProduct($options = [])
    {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        if (isset($options['price_min']) && $options['price_min']) {
            $condition .= " AND price >= " . $options['price_min'];
        }
        if (isset($options['price_max']) && $options['price_max']) {
            $condition .= " AND price <= " . $options['price_max'];
        }
        //
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (category_track) AGAINST ('" . $options['category_id'] . "' IN BOOLEAN MODE)";
        }
        //
        if (isset($options['keyword']) && $options['keyword']) {
            $first_character = substr($options['keyword'], 0, 1);
            if (isset($first_character) && $first_character == '#') {
                $options['keyword'] = ltrim($options['keyword'], '#');
                $condition .= ' AND id=:id';
                $params[':id'] = $options['keyword'];
            } else {
                $condition .= ' AND name LIKE :name';
                $params[':name'] = '%' . $options['keyword'] . '%';
            }
        }
        //
        if (isset($options['brand']) && $options['brand']) {
            $condition .= ' AND brand=:brand';
            $params[':brand'] = $options['brand'];
        }
        //
        $count = (new Query())->select('*')
            ->from('product')
            ->where($condition, $params)
            ->count();
        return $count;
    }

    public static function getProductByAttr($options = [])
    {

        $where = "status = 1";
        $params = [];
        if (isset($options['attr']) && $options['attr']) {
            foreach ($options['attr'] as $key => $value) {
                $where .= " AND $key = :{$key} ";
                $params[$key] = $value;
            }
        }

        if (isset($options['_product']) && $options['_product']) {
            $where .= " AND id <> :_product ";
            $params['_product'] = $options['_product'];
        }
        // if(!isset($_GET['province_id']) &&  !isset($options['attr']['province_id'])) {
        //     $province_id = \common\components\ClaLid::getProvinceDefault();
        //     $where .= " AND province_id = '$province_id' ";
        // }
        $order = Product::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }

        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        $products = (new Query())->select('*')
            ->from('product')
            ->where($where, $params)
            ->orderBy($order)
            ->limit($limit)
            ->all();
        return $products;
    }

    public static function getRelationProducts($options = [])
    {
        $cat_id = (int) $options['category_id'];
        $product_id = (int) $options['product_id'];
        if (!$cat_id || !$product_id) {
            return [];
        }
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        $condition .= " AND MATCH (category_track) AGAINST ('" . $cat_id . "' IN BOOLEAN MODE)";
        //
        $condition .= ' AND id<>:id';
        $params[':id'] = $product_id;
        //
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        $products = (new Query())->select('*')
            ->from('product')
            ->where($condition, $params)
            ->orderBy("ABS(id - $product_id)")
            ->limit($limit)
            ->all();
        return $products;
    }

    public static function getProductConfigurable($parent_id)
    {
        $data = Product::find()
            ->where('parent_id=:parent_id AND is_configurable=:is_configurable', [':parent_id' => $parent_id, ':is_configurable' => ClaLid::STATUS_ACTIVED])
            ->asArray()
            ->all();
        //
        return $data;
    }

    static function getDataSearch($option)
    {
        if ($option['tag']) {
            $tag = self::changeKey($option['tag']);
        } else {
            $tag = null;
        }
        if (isset($option['limit'])) {
            $limit = $option['limit'];
        } else {
            $limit = \common\models\Search::LIMIT;
        }
        if (isset($option['page'])) {
            $page = $option['page'];
        } else {
            $page = 1;
        }
        $data = (new \yii\db\Query())
            ->from('product')
            ->select('id, name as title, alias, meta_keywords, short_description as content, avatar_name, avatar_path')
            ->where(['like', 'alias', $tag])
            ->orWhere(['like', 'meta_keywords', $tag])
            ->orderBy('id DESC')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['type'] = \common\models\Search::getTypeName(\common\models\Search::TYPE_PRO);
            $data[$i]['link'] = \yii\helpers\Url::to(['/product/product/detail', 'id' => $data[$i]['id'], 'alias' => $data[$i]['alias']]);
        }
        $tg['data'] = $data;
        $count = (new \yii\db\Query())
            ->from('product')
            ->where(['like', 'name', $tag])
            ->orWhere(['like', 'meta_keywords', $tag])
            ->count();
        $tg['pagination'] = new \yii\data\Pagination([
            'defaultPageSize' => $limit,
            'totalCount' => $count,
        ]);

        return $tg;
    }

    public function getPriceC1($quantity)
    {
        $item = \common\models\promotion\ProductToPromotions::getPromotionItemNowByProduct($this->id);
        if ($item) {
            return $item->price;
        }
        if ($this->price_range && $this->quality_range && $quantity) {
            $price_range = explode(',', $this->price_range);
            $quality_range = explode(',', $this->quality_range);
            for ($i = count($quality_range) - 1; $i > 0; $i--) {
                if ($quality_range[$i] <= $quantity && $quality_range[$i] > 0) {
                    return (isset($price_range[$i]) && $price_range[$i]) ? $price_range[$i] : $price_range[$i - 1];
                }
            }
            return $price_range[0];
        }
        return $this->price;
    }

    public function getPrice($quantity)
    {
        $price = $this->getPriceC1($quantity);
        if (!is_numeric($this->shop_status_affiliate)) {
            $shop = \common\models\shop\Shop::getOneMuch($this->shop_id);
            $this->shop_status_affiliate =  $shop ?  $shop->status_affiliate : 0;
        }
        if ($price > 0 && $this->status_affiliate == \common\components\ClaLid::STATUS_ACTIVED && $this->shop_status_affiliate == \common\components\ClaLid::STATUS_ACTIVED && $this->affiliate_safe > 0) {
            return $this->_price = intval($price - ($this->affiliate_safe * $price / 100));
        }
        return $this->_price = intval($price);
    }

    public function getTextByPrice($price, $show_unit = true)
    {
        if ($price <= 0) {
            return 'Liện hệ';
        }
        if ($this->category_id == \common\models\product\ProductCategory::CATEGORY_SALE) {
            return formatMoney(\common\models\gcacoin\Gcacoin::getCoinToMoney($this->_price)) . ' ' . __VOUCHER_SALE;
        }
        return number_format($price, 0, ',', '.') . ($show_unit ? ' ' . Yii::t('app', 'currency') : '');
    }

    public function getPriceText($quantity, $show_unit = true)
    {
//        if ($this->category_id == \common\models\product\ProductCategory::CATEGORY_SALE) {
//            if (!$this->_price) {
//                $this->getPrice($quantity);
//            }
//            if ($this->_price > 0) {
//                return formatMoney(\common\models\gcacoin\Gcacoin::getCoinToMoney($this->_price)) . ' ' . __VOUCHER_SALE;
//            }
//            return 'Liện hệ';
//        }
//        if ($this->_price_text !== null) {
//            return $show_unit ? $this->_price_text : str_replace(' ' . Yii::t('app', 'currency'), '', $this->_price_text);
//        }
//        $this->_price = ($this->_price === null) ? $this->getPrice($quantity) : $this->_price;
//        if ($this->_price > 0) {
//            $this->_price_text = $this->getTextByPrice($this->_price, false);
//            if ($this->price_range) {
//                $price_max = $this->getPrice($quantity);
//                $price_min = $this->getPrice(MAX_QUANTITY_PRODUCT);
//                if ($price_max != $price_min) {
//                    $this->_price_text = $this->getTextByPrice($price_min, false) . ' - ' . $this->getTextByPrice($price_max, false);
//                    $this->_price_market_text = '';
//                } else {
//                    $this->_price_text = $this->getTextByPrice($price_min, false);
//                }
//            }
//            return $this->_price_text . ($show_unit ? ' ' . Yii::t('app', 'currency') : '');
//        }
        return $this->_price_text = 'Liên hệ';
    }

    public static function getPriceStaticC1($product, $quantity = 1)
    {
        $item = \common\models\promotion\ProductToPromotions::getPromotionItemNowByProduct($product['id']);
        if ($item) {
            return $item->price;
        }
        if ($product['price_range'] && $product['quality_range'] && $quantity) {
            $price_range = explode(',', $product['price_range']);
            $quality_range = explode(',', $product['quality_range']);
            for ($i = count($quality_range) - 1; $i > 0; $i--) {
                if ($quality_range[$i] <= $quantity && $quality_range[$i] > 0) {
                    $price = (isset($price_range[$i]) && $price_range[$i]) ? $price_range[$i] : $price_range[$i - 1];
                    return $price;
                }
            }
            return $price_range[0];
        }
        return $product['price'];
    }

    public static function getPriceStatic($product, $quantity = 1)
    {
        $price = self::getPriceStaticC1($product, $quantity);
        if ($price > 0  && $product['status_affiliate'] == \common\components\ClaLid::STATUS_ACTIVED) {
            if ($product['affiliate_safe']) {
                return $price - ($price * $product['affiliate_safe'] / 100);
            }
        }
        return $price;
    }

    public function getPriceMarket($quantity)
    {
        if ($this->price_market > 0) {
            return  intval($this->price_market);
        }
        if ($this->price_range && $this->quality_range && $quantity) {
            $price_range = explode(',', $this->price_range);
            $quality_range = explode(',', $this->quality_range);
            for ($i = count($quality_range) - 1; $i > 0; $i--) {
                if ($quality_range[$i] <= $quantity && $quality_range[$i] > 0) {
                    return $this->_price_market = intval((isset($price_range[$i]) && $price_range[$i]) ? $price_range[$i] : $price_range[$i - 1]);
                }
            }
            return $this->_price_market = intval($price_range[0]);
        }
        return $this->_price_market = intval($this->price);
    }

    public function getPriceMarketText($quantity)
    {
        if ($this->_price_market_text !== null) {
            return $this->_price_market_text;
        }
        $this->_price_market = ($this->_price_market === null) ? $this->getPriceMarket($quantity) : $this->_price_market;
        if ($this->_price_market > 0) {
            $this->_price_market_text =  $this->getTextByPrice($this->_price_market);
            if ($this->price_range) {
                $price_max = $this->getPrice($quantity);
                $price_min = $this->getPrice(MAX_QUANTITY_PRODUCT);
                if ($price_max != $price_min) {
                    $this->_price_text = $this->getTextByPrice($price_min, false) . ' - ' . $this->getTextByPrice($price_max);
                    $this->_price_market_text = '';
                } else {
                    $this->_price_text = $this->getTextByPrice($price_min);
                }
            }
            return $this->_price_market_text;
        }
        return $this->_price_market_text = '';
    }

    public static function getPriceMarketStatic($product, $quantity)
    {
        if ($product['price_market'] > 0) {
            return $product['price_market'];
        }
        if ($product['price_range'] && $product['quality_range'] && $quantity) {
            $price_range = explode(',', $product['price_range']);
            $quality_range = explode(',', $product['quality_range']);
            for ($i = count($quality_range) - 1; $i > 0; $i--) {
                if ($quality_range[$i] <= $quantity && $quality_range[$i] > 0) {
                    $price = (isset($price_range[$i]) && $price_range[$i]) ? $price_range[$i] : $price_range[$i - 1];
                    return $price;
                }
            }
            return $price_range[0];
        }
        return $product['price'];
    }

    public function userBeforeProduct()
    {
        $affiliate_id_cookie = ClaLid::getCookie(\common\models\affiliate\AffiliateLink::AFFILIATE_NAME . $this->id);
        if (isset($affiliate_id_cookie) && $affiliate_id_cookie) {
            $affiliate = \common\models\affiliate\AffiliateLink::findOne($affiliate_id_cookie);
            if ($affiliate && $affiliate->user_id) {
                return $affiliate->user_id;
            }
        }
        return 0;
    }

    public function userBeforeShop()
    {
        $user = \frontend\models\User::findOne($this->shop_id);
        if ($user && $user->user_before > 0) {
            return $user->user_before;
        }
        return 0;
    }

    public function getQuantityMin()
    {
        return $this->quality_range ? explode(',', $this->quality_range)[0] : 1;
    }

    public function getQuantityMax()
    {
        $quantity_max = MAX_QUANTITY_PRODUCT;
        $promotion = \common\models\promotion\Promotions::getPromotionNow();
        if ($promotion) {
            $promotion_item = $this->getPromotionItemNow();
            if ($promotion_item) {
                $quantity_max = ($promotion_item->quantity - $promotion_item->quantity_selled) > 0 ? ($promotion_item->quantity - $promotion_item->quantity_selled) : $quantity_max;
            }
        }
        return $quantity_max;
    }

    public function getPromotionItemNow()
    {
        return \common\models\promotion\ProductToPromotions::getPromotionItemNowByProduct($this->id);
    }
    public function getPromotionItem()
    {
        return \common\models\promotion\ProductToPromotions::getPromotionItemByProduct($this->id);
    }

    public function getQuatityOrder($quantity)
    {
        $quantity_min = $this->getQuantityMin();
        $quantity_max = $this->getQuantityMax();
        if ($quantity_min <= $quantity && $quantity <= $quantity_max) {
            return $quantity;
        } else {
            if ($quantity_min <= $quantity) {
                return $quantity_max;
            } else {
                return $quantity_min;
            }
        }
    }

    public static function getOrderInShop($status_order)
    {
        $data = (new Query())
            ->select('p.*,t.id as t_id, t.price as t_price, t.quantity as t_quantity, t.created_at as t_created_at, s.name as s_name, s.alias as s_alias, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, o.address as o_address, o.district_id as o_district_id, o.province_id as o_province_id')
            ->from('order_item t')
            ->leftJoin('order o', 't.order_id = o.id')
            ->leftJoin('product p', 't.product_id = p.id')
            ->leftJoin('shop s', 'p.shop_id = s.id')
            ->where(['t.status' => $status_order, 'p.shop_id' => Yii::$app->user->id])
            ->all();
        return $data;
    }

    public static function getOrderInShopById($t_id)
    {
        $data = (new Query())
            ->select('p.*,t.id as t_id, t.price as t_price, t.quantity as t_quantity, t.created_at as t_created_at, s.name as s_name, s.alias as s_alias, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, o.name as o_name, o.address as o_address, o.district_id as o_district_id, o.province_id as o_province_id')
            ->from('order_item t')
            ->leftJoin('order o', 't.order_id = o.id')
            ->leftJoin('product p', 't.product_id = p.id')
            ->leftJoin('shop s', 'p.shop_id = s.id')
            ->where(['t.id' => $t_id, 'p.shop_id' => Yii::$app->user->id])
            ->one();
        return $data;
    }

    public static function getOrderById($t_id)
    {
        $data = (new Query())
            ->select('p.*,t.id as t_id, t.price as t_price, t.quantity as t_quantity, t.created_at as t_created_at, s.name as s_name, s.alias as s_alias, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, o.name as o_name, o.address as o_address, o.phone as o_phone')
            ->from('order_item t')
            ->leftJoin('order o', 't.order_id = o.id')
            ->leftJoin('product p', 't.product_id = p.id')
            ->leftJoin('shop s', 'p.shop_id = s.id')
            ->where(['t.id' => $t_id])
            ->one();
        return $data;
    }

    public static function getOrderByUser($status_order)
    {
        $data = (new Query())
            ->select('p.*,t.id as t_id, t.price as t_price, t.quantity as t_quantity, t.created_at as t_created_at, s.name as s_name, s.alias as s_alias, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name')
            ->from('order_item t')
            ->leftJoin('order o', 't.order_id = o.id')
            ->leftJoin('product p', 't.product_id = p.id')
            ->leftJoin('shop s', 'p.shop_id = s.id')
            ->where(['t.status' => $status_order, 'o.user_id' => Yii::$app->user->id])
            ->all();
        return $data;
    }
    public static function getItiemInShop($shop_id)
    {
        $data = [-1];
        $data = (new Query())->select('category_id')
            ->from('product')
            ->distinct('category_id')
            ->where("shop_id = '$shop_id' AND status=1")
            ->all();
        return array_column($data, 'category_id');
    }

    public static function updateRate($id)
    {
        $rate_count = Rating::find()->where(['object_id' => $id])->count();
        $sum = (new \yii\db\Query())->from('rating')->select('SUM(rating) as tong')->where(['object_id' => $id])->one();
        if ($rate_count) {
            $rate = 1.00 *  $sum['tong'] / $rate_count;
        } else {
            $rate_count = '';
        }
        if ((new Query())->createCommand()->update(
            'product',
            [
                'rate_count' => $rate_count,
                'rate' => $rate,
            ],
            'id = ' . $id
        )->execute()) {
            $product = self::findOne($id);
            if ($product && $product->shop_id) {
                \common\models\shop\Shop::updateRating($product->shop_id);
            }
            return 1;
        }
    }

    static function getProductAffiliate($user_id)
    {
        return self::find()->where(['status_affiliate' => 1, 'shop_id' => $user_id])->orderBy('name')->all();
    }

    function getLinkAffiliate($user_id)
    {
        $item = \common\models\affiliate\AffiliateLink::find()->where(['user_id' => $user_id, 'object_id' => $this->id, 'type' => 1])->one();
        if ($item) {
            return $item->link;
        }
        return '';
    }

    public static function loadShowAll()
    {
        $model = new self();
        $model->_provinces = \common\models\Province::optionsProvince();
        $model->_shops = \common\models\shop\Shop::optionsShop();
        $model->_wishs = \common\models\product\ProductWish::getWishAllByUser();
        return $model;
    }

    public function getLink()
    {
        return \yii\helpers\Url::to(['/product/product/detail', 'id' => $this->id, 'alias' => $this->alias]);
    }

    function setAttributeShow($attr)
    {
        $this->setAttributes($attr, false);
        $this->_price = null;
        $this->_price_market = null;
        $this->_price_market_text = null;
        $this->_price_text = null;
    }

    function checkInCart()
    {
        return \frontend\components\Shoppingcart::check($this->code . $this->id);
    }

    function show($attr)
    {
        // $model->_provinces = \common\models\Province::optionsProvince();
        // $model->_shops = \common\models\shop\Shop::optionsShop();
        // $model->_wishs = \common\models\product\ProductWish::getWishAllByUser();
        $value = $this->$attr;
        switch ($attr) {
            case 'shop_id':
                if ($this->_shops) {
                    return isset($this->_shops[$this->shop_id]) ? $this->_shops[$this->shop_id]['name'] : '';
                } else {
                    $model = \common\models\shop\Shop::findOne($value);
                    return $model ? $model->name : '';
                }
                return isset($shops[$value]) ? $shops[$value]['name'] : '';
            case 'province_id':
                if ($this->_provinces) {
                    return isset($this->_provinces[$value]) ? $this->_provinces[$value] : '';
                } else {
                    $model = \common\models\Province::findOne($value);
                    return $model ? $model->name : '';
                }
        }
        return $value;
    }

    function getShop()
    {
        if ($this->_shops) {
            return isset($this->_shops[$this->shop_id]) ? $this->_shops[$this->shop_id] : [];
        } else {
            return \common\models\shop\Shop::findOne($this->shop_id);
        }
    }

    function inWish()
    {
        if ($this->_wishs === null) {
            $this->_wishs = \common\models\product\ProductWish::getWishAllByUser();
        }
        if ($this->_wishs) {
            return in_array($this->id, $this->_wishs);
        }
        return false;
    }

    function inCatSale()
    {
        return $this->category_id == \common\models\product\ProductCategory::CATEGORY_SALE;
    }

    function getLinkBuy()
    {
        switch ($this->category_id) {
            case \common\models\product\ProductCategory::CATEGORY_SALE:
                return \yii\helpers\Url::to(['/product/shoppingcartv/index', 'id' => $this->id]);
        }
        return \yii\helpers\Url::to(['/product/shoppingcart/add-cart', 'id' => $this->id]);
    }

    function findActive($id)
    {
        return self::find()->where(['status' => 1, 'id' => $id])->one();
    }

    function canBuyFor($user_id)
    {
        $category = $this->category();
        if (!$category) {
            $this->_merrors = 'Sản phẩm ' . $this->name . ' không tồn tại.';
            return false;
        }
        if (!$category->inGroup()) {
            return true;
        }
        if ($user_id) {
            if (in_array($category->id, \common\models\user\UserInGroup::getListCatAllow($user_id))) {
                return true;
            }
        }
        $this->_merrors = 'Sản phẩm ' . $this->name . ' không thuộc nhóm có thể mua.';
        return false;
    }

    function category()
    {
        return ProductCategory::find()->where(['id' => $this->category_id, 'status' => 1])->one();
    }

    static function getProductService($shop_id)
    {
        return self::find()->where(['shop_id' => $shop_id, 'pay_servive' => 1])->one();
    }
}

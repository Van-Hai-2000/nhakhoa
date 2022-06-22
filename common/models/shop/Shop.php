<?php

namespace common\models\shop;

use yii\db\Query;
use Yii;
use common\components\ClaLid;

/**
 * This is the model class for table "{{%shop}}".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property integer $type
 * @property integer $user_id
 * @property string $address
 * @property string $province_id
 * @property string $province_name
 * @property string $district_id
 * @property string $district_name
 * @property string $ward_id
 * @property string $ward_name
 * @property string $image_path
 * @property string $image_name
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $phone
 * @property string $hotline
 * @property string $email
 * @property string $yahoo
 * @property string $skype
 * @property string $website
 * @property string $facebook
 * @property string $instagram
 * @property string $pinterest
 * @property string $twitter
 * @property string $field_business
 * @property integer $status
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $site_id
 * @property integer $allow_number_cat
 * @property string $short_description
 * @property string $description
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_title
 * @property integer $avatar_id
 * @property integer $time_open
 * @property integer $time_close
 * @property integer $day_open
 * @property integer $day_close
 * @property integer $type_sell
 * @property integer $like
 * @property string $policy
 * @property string $contact
 * @property string $latlng
 * @property string $payment_transfer
 * @property string $category_track
 * @property integer $level
 * @property string $number_auth
 * @property string $date_auth
 * @property string $address_auth
 * @property string $number_paper_auth
 */
class Shop extends \common\models\ActiveRecordC
{
    const ONE_MEMBER = 1;
    const MANY_MEMBER = 2;
    const IMG_AUTH = 2;
    const DEFAULT_LIMIT = 12;
    const DEFAULT_ORDER = 'id DESC ';

    const ID_SHOP_BQT = 2041;
    const ID_SHOP_CHARITY = 3300;
    const ID_SHOP_FEETRAN = 3298;

    public $avatar1 = '';
    public $avatar2 = '';
    public $time_limit_type_term;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            // ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'Email đã tồn tại.'],
            [['name', 'type', 'province_id', 'district_id', 'ward_id', 'name_contact', 'phone', 'cmt', 'address'], 'required'],
            [['user_id', 'allow_number_cat', 'avatar_id', 'time_open', 'time_close', 'day_open', 'day_close', 'type_sell', 'like', 'cmt', 'shop_acount_type'], 'integer'],
            [['description', 'policy', 'contact', 'payment_transfer'], 'string'],
            [['name', 'address', 'image_name', 'avatar_path', 'avatar_name', 'email', 'yahoo', 'skype', 'website', 'facebook', 'instagram', 'pinterest', 'twitter', 'field_business', 'meta_keywords', 'meta_description', 'meta_title', 'latlng', 'category_track', 'address_auth'], 'string', 'max' => 255],
            // [['province_id', 'district_id'], 'string', 'max' => 10],
            [['province_name', 'district_name', 'ward_name'], 'string', 'max' => 100],
            [['number_auth', 'number_paper_auth'], 'string', 'max' => 20],
            [['phone'], 'integer'],
            [['cmt', 'status_affiliate_waitting', 'affiliate_admin_waitting', 'affiliate_gt_shop_waitting', 'affilliate_status_service_waitting'], 'integer'],
            [['phone'], 'string', 'min' => 9, 'max' => 13],
            [['cmt'], 'string', 'min' => 9, 'max' => 20],
            [['image_path'], 'string', 'max' => 200],
            [['hotline', 'name_contact'], 'string', 'max' => 50],
            [['short_description'], 'string', 'max' => 1000],
            [['date_auth'], 'string', 'max' => 12],
            [['avatar1', 'avatar2', 'scale', 'latlng', 'lat', 'lng', 'type', 'level', 'time_limit_type_term', 'zalo'], 'safe'],
            [['status', 'account_status', 'status_discount_code'], 'integer', 'on' => 'backend']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'shop_name'),
            'name_contact' => Yii::t('app', 'name_contact'),
            'alias' => 'Alias',
            'type' => Yii::t('app', 'shop_type'),
            'user_id' => 'User ID',
            'address' => Yii::t('app', 'address'),
            'province_id' => Yii::t('app', 'province_name'),
            'province_name' => Yii::t('app', 'province_name'),
            'district_id' => Yii::t('app', 'district_name'),
            'district_name' => Yii::t('app', 'district_name'),
            'ward_id' => Yii::t('app', 'ward_name'),
            'ward_name' => Yii::t('app', 'ward_name'),
            'image_path' => Yii::t('app', 'image_path'),
            'image_name' => Yii::t('app', 'image_name'),
            'avatar_path' => Yii::t('app', 'avatar_path'),
            'avatar_name' => Yii::t('app', 'avatar_name'),
            'phone' => Yii::t('app', 'phone'),
            'hotline' => Yii::t('app', 'hotline'),
            'email' => Yii::t('app', 'email'),
            'yahoo' => Yii::t('app', 'yahoo'),
            'skype' => Yii::t('app', 'skype'),
            'website' => Yii::t('app', 'website'),
            'facebook' => Yii::t('app', 'facebook'),
            'instagram' => Yii::t('app', 'instagram'),
            'pinterest' => Yii::t('app', 'pinterest'),
            'twitter' => Yii::t('app', 'twitter'),
            'field_business' => Yii::t('app', 'field_business'),
            'status' => Yii::t('app', 'status'),
            'created_time' => Yii::t('app', 'created_time'),
            'modified_time' => Yii::t('app', 'modified_time'),
            'site_id' => Yii::t('app', 'site_id'),
            'allow_number_cat' => Yii::t('app', 'allow_number_cat'),
            'short_description' => Yii::t('app', 'short_description'),
            'description' => Yii::t('app', 'description'),
            'meta_keywords' => Yii::t('app', 'meta_keywords'),
            'meta_description' => Yii::t('app', 'meta_description'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'avatar_id' => 'Avatar ID',
            'time_open' => Yii::t('app', 'time_open'),
            'time_close' => Yii::t('app', 'time_close'),
            'day_open' => Yii::t('app', 'day_open'),
            'day_close' => Yii::t('app', 'day_close'),
            'type_sell' => Yii::t('app', 'type_sell'),
            'like' => Yii::t('app', 'like'),
            'policy' => Yii::t('app', 'policy'),
            'contact' => Yii::t('app', 'contact'),
            'latlng' => Yii::t('app', 'latlng'),
            'payment_transfer' => Yii::t('app', 'payment_transfer'),
            'category_track' => Yii::t('app', 'category_track'),
            'number_auth' => Yii::t('app', 'number_auth'),
            'date_auth' => Yii::t('app', 'date_auth'),
            'address_auth' => Yii::t('app', 'address_auth'),
            'number_paper_auth' => Yii::t('app', 'number_paper_auth'),
            'avatar1' => Yii::t('app', 'avatar1'),
            'avatar2' => Yii::t('app', 'avatar2'),
            'cmt' => Yii::t('app', 'cmt'),
            'level' => Yii::t('app', 'shop_level'),
            'scale' => Yii::t('app', 'shop_scale'),
            'status_affiliate' => 'Tham gia Affiliate',
            'affiliate' => 'Chiết khấu:(0-100%)',
            'shop_acount_type' => 'Loại tài khoản gian hàng',
            'account_status' => 'Xác thực gian hàng',
            'status_affiliate_waitting' => 'Tham gia affiliate',
            'affiliate_admin_waitting' => '% thưởng cho OCOP PARTNER',
            'affiliate_gt_shop_waitting' => '% thưởng cho người giới thiệu DN của bạn',
            'time_limit_type_term' => 'Thời hạn doanh nghiệp',
            'status_discount_code' => 'Tạo mã giảm giá',
            'affilliate_status_service' => 'Kích hoạt QR-CODE dịch vụ',
            'affiliate_admin' => '% thưởng cho OCOP PARTNER',
            'affiliate_gt_shop' => '% thưởng cho người giới thiệu DN của bạn',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_time = time();
                if (!$this->avatar_name && !$this->avatar_path) {
                    $this->avatar_path = '/media/images/shop/2019_01_02/';
                    $this->avatar_name = 'df-1546395774.png';
                }
                if (!$this->image_name && !$this->image_path) {
                    $this->image_path = '/media/images/shop/2019_01_02/';
                    $this->image_name = 'df-1546395774.png';
                }
                \common\models\NotificationAdmin::addNotifcation('shop');
            }
            if (!$this->latlng) {
                $ward = \common\models\Ward::findOne($this->ward_id);
                $this->latlng = $ward ? $ward->latlng : $this->latlng;
            }

            $latlng = explode(',', $this->latlng);
            $this->lat = isset($latlng[0]) ? $latlng[0] : 0;
            $this->lng = isset($latlng[1]) ? $latlng[1] : 0;
            $this->modified_time = time();
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            if (!$this->checkUpdateAffialite()) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function user()
    {
        return \frontend\models\User::findOne($this->user_id);
    }

    function checkUpdateAffialite()
    {
        if ($this->status_affiliate_waitting != 1) {
            return true;
        }
        $max = (new \yii\db\Query())->select('(affiliate_gt_product + affiliate_m_v + affiliate_m_ov +affiliate_safe) as tf')->from('product')->where("shop_id = " . $this->id . " and status_affiliate = 1")->orderBy('tf DESC')->limit(1)->all();
        $gt_max = $max ? $max[0]['tf'] : 0;
        if (($gt_max + $this->affiliate_admin_waitting + $this->affiliate_gt_shop_waitting) <= 100) {
            return true;
        }
        $this->addError('status_affiliate_waitting', 'Tổng % affiliate vượt quá 100% vui lòng kiểm tra lại.');
        return false;
    }

    static function getOptionsTypeAcount()
    {
        return [
            1 => 'Nhà sản xuất',
            2 => 'Nhà phân phối'
        ];
    }

    function getNameTypeAcount()
    {
        $ls = self::getOptionsTypeAcount();
        return isset($ls[$this->shop_acount_type]) ? $ls[$this->shop_acount_type] : 'Chưa phân loại';
    }

    public static function getLimitType()
    {
        $types = [
            1 => 'Gói miễn phí 12 tháng đầu',
            0 => 'Gói vô thời hạn',
        ];
        return $types;
    }

    public static function getType($id = null)
    {
        $types = [
            '' => Yii::t('app', 'select_shop_type'),
            1 => Yii::t('app', 'shop_type_1'),
            2 => Yii::t('app', 'shop_type_2'),
            3 => Yii::t('app', 'shop_type_3'),
            4 => Yii::t('app', 'shop_type_4')
        ];

        if ($id) {
            $list = explode(' ', $id);
            $html = '';
            foreach ($types as $key => $value) {
                $html .= in_array($key, $list) ? $value . ', ' : '';
            }
            return $html;
        }
        return $types;
    }

    public static function getScale($id = null)
    {
        $types = [
            Yii::t('app', 'small') => Yii::t('app', 'small'),
            Yii::t('app', 'median') => Yii::t('app', 'median'),
            Yii::t('app', 'large ') => Yii::t('app', 'large'),
            Yii::t('app', 'vrlarge') => Yii::t('app', 'vrlarge'),
        ];
        if ($id && isset($types[$id])) return $types[$id];
        return $types;
    }

    public static function updateAddress($address)
    {
        // echo "<pre>";
        // print_r($address);
        if ($model = Shop::findOne($address->shop_id)) {
            $model->province_id = $address->province_id;
            $model->province_name = $address->province_name;
            $model->district_id = $address->district_id;
            $model->district_name = $address->district_name;
            $model->ward_id = $address->ward_id;
            $model->ward_name = $address->ward_name;
            $model->latlng = $address->latlng;
            $model->address = $address->address;
            $model->name_contact = $address->name_contact;
            $model->phone = $address->phone;
            $model->phone_add = $address->phone_add;
            if ($model->latlng) {
                $tgss = explode(',', $model->latlng);
                if (count($tgss) > 1) {
                    $model->lat = $tgss[0];
                    $model->lng = $tgss[1];
                }
            }
            return $model->save();
        }
        return 0;
    }

    public static function optionsShop()
    {
        $shops = (new Query())->select('*')
            ->from('shop')
            ->all();
        $data = [];
        foreach ($shops as $shop) {
            $data[$shop['id']] = $shop;
        }
        return $data;
    }

    public static function optionShop()
    {
        $shops = Shop::find()->all();
        $data = ['' => Yii::t('app', 'select_shop')];
        if ($shops) {
            foreach ($shops as $shop) {
                $data[$shop['id']] = $shop['name'];
            }
        }
        return $data;
    }

    public static function getShop($options = [])
    {
        $condition = '1 = 1';
        $params = '';
        if (!isset($options['status'])) {
            $condition = 's.status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
        }

        $query = (new Query());
        //
        if (isset($options['province_id']) && $options['province_id']) {
            $condition .= " AND s.province_id = :province_id";
            $params[':province_id'] = $options['province_id'];
        }

        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (t.category_track) AGAINST ('" . __removeDF($options['category_id']) . "' IN BOOLEAN MODE)";
        }
        if (isset($options['type_gold']) && !isset($options['type_rate'])) {
            $condition .= " AND s.level > 1";
            $options['order'] = ' s.level DESC, t.ishot DESC, t.id DESC';
        }
        if (isset($options['type_rate']) && !isset($options['type_gold'])) {
            $condition .= " AND s.rate > 1";
            $options['order'] = ' s.rate DESC, t.ishot DESC, t.id DESC';
        }

        if (isset($options['type_rate']) && isset($options['type_gold'])) {
            $condition .= " AND (s.rate > 1 OR s.level > 1) ";
            $options['order'] = ' s.level DESC, s.rate DESC, t.ishot DESC, t.id DESC';
        }

        if (isset($options['address']) && $options['address']) {
            $condition .= " AND s.address LIKE :address";
            $params[':address'] = '%' . $options['address'] . '%';
        }
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND s.ishot=:ishot';
            $params[':ishot'] = $options['ishot'];
        }
        //
        $limit = 's.' . Shop::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        if (isset($options['page'])) {
            $offset = ($options['page'] - 1) * $limit;
        } else {
            $offset = 0;
        }
        $order = Shop::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = __removeDF($options['order']);
        }
        if (isset($options['level']) && $options['level']) {
            $condition .= ' AND s.level=:level';
            $params[':level'] = $options['level'];
        }
        if (isset($options['type']) && $options['type']) {
            $condition .= ' AND s.type=:type';
            $params[':type'] = $options['type'];
        }
        if (isset($options['count'])) {
            $select = 's.*';
        } else {
            $select = 's.*, COUNT(t.id) as count_product';
        }
        if (isset($options['keyword']) && $options['keyword']) {
            $tag = $options['keyword'];
            $tag = str_replace(',', ' ', $tag);
            if ($tag) {
                $select = $select . ", ((1.8 * (MATCH(s.name) AGAINST (:tag IN BOOLEAN MODE)))) as score ";
                // $query->select($select . ", ((1.8 * (MATCH(s.name) AGAINST (:tag IN BOOLEAN MODE)))) as score ");
                $query->andWhere("(MATCH(s.name) AGAINST (:tag IN BOOLEAN MODE))");
                $query->having('score > 1');
                $order = 'score DESC, ' . $order;
                $params[':tag'] = $tag;
            }
        }
        //
        if (isset($options['count'])) {
            $total = $query->select($select)
                ->from('shop AS s')
                ->leftJoin('product AS t', 't.shop_id = s.id')
                ->where($condition, $params)
                ->distinct('s.id')
                ->count();
            return $total;
        }
        $data = (new Query())->select($select)
            ->from('shop AS s')
            ->leftJoin('product AS t', 't.shop_id = s.id')
            ->andWhere($condition, $params)
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->distinct('s.name')
            ->groupBy('s.id')
            ->all();
        return $data;
    }

    public function getListLevel()
    {
        return $this->level ? \common\models\shop\ShopLevel::find()->where(['id' => explode(' ', $this->level)])->all() : [];
    }

    public static function updateRating($id)
    {
        $shop = self::findOne($id);
        if ($shop) {
            $list_product = \common\models\product\Product::find()->where(['shop_id' => $id])->andWhere(' rate_count > 0 ')->all();
            $shop->rate = 0;
            $shop->rate_count = 0;
            if ($list_product) {
                foreach ($list_product as $product) {
                    $shop->rate += $product->rate;
                    $shop->rate_count += $product->rate_count;
                }
                $shop->rate =  round($shop->rate / count($list_product), 2);
                $shop->save(false);
            }
        }
    }

    public static function getAfterAffiliate($user_id)
    {
        return self::find()->leftJoin('user', 'user.id = shop.id')->where(['user_before' => $user_id])->all();
    }

    public function afterSave($insert, $changedAttributes)

    {
        if ($this->email && isset($changedAttributes['status']) && $this->status == 1) {
            \common\models\mail\SendEmail::sendMail([
                'title' => '[Thông báo] Gian hàng đã được duyệt',
                'content' => '<p>Gian hàng <b>' . $this->name . '</b> của quý khách đã được duyệt.</p><p>Quý khách có thể sử dụng những tính năng của gian hàng và đăng bán sản phẩm trên ocopmart.org ngay từ bây giờ</p><p><a href="<?= __SERVER_NAME ?>/quan-ly-san-pham.html">Đăng sản phẩm</a></p>',
                'email' => $this->email,
            ]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    static function checkAccountStatus($user_id)
    {
        $shop = $user_id ? \common\models\shop\Shop::findOne($user_id) : 0;
        if ($shop && $shop->account_status == 1) {
            return true;
        }
        return false;
    }

    function getSumAffCheck()
    {
        $v1 = 0;
        if ($this->status_affiliate == 1) {
            $v1 = $this->affiliate_admin + $this->affiliate_gt_shop;
        }
        $v2 = 0;
        if ($this->status_affiliate_waitting == 1) {
            $v2 = $this->affiliate_admin_waitting + $this->affiliate_gt_shop_waitting;
        }
        return $v1 > $v2 ? $v1 : $v2;
    }

    function getSumAffBuy()
    {
        $v1 = 0;
        if ($this->status_affiliate == 1) {
            $v1 = $this->affiliate_admin + $this->affiliate_gt_shop;
        }
        return $v1;
    }

    function changeAffilliateServiceUser($coin)
    {
        if ($this->affilliate_status_service == 1 && $this->status_affiliate) {
            // if ($product = \common\models\product\Product::getProductService($this->id)) {
            //     $re = $coin - ($product->affiliate_safe * $coin / 100);
            return $coin;
            // }
        }
        $siteif = \common\models\gcacoin\Config::getConfig();
        return $siteif->getCoinTransferFee($coin);
    }

    function changeAffilliateService($coin)
    {
        if ($this->affilliate_status_service == 1 && $this->status_affiliate) {
            if ($product = \common\models\product\Product::getProductService($this->id)) {
                $re = $coin - ($product->affiliate_safe * $coin / 100);
            }
            return $re;
        }
        return $coin;
    }

    function affilliateService($coin_pay, $user)
    {
        if ($this->affilliate_status_service == 1 && $this->status_affiliate) {
            $order = new \common\models\order\Order();
            $order->payment_method = \common\components\payments\ClaPayment::PAYMENT_METHOD_CKQR;
            $order->payment_method_child = 'CHUYENKHOANQR';
            $order->order_total_all = $order->order_total = \common\models\gcacoin\Gcacoin::getMoneyToCoin($coin_pay);
            $order->user_id = $user->id;
            $order->shop_id = $this->id;
            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
            $order->status = \common\models\order\Order::ORDER_DELIVERING;
            $order->getAddress();
            $order->key = strtoupper(md5(time()));
            $product = \common\models\product\Product::getProductService($this->id);
            if (!$product) {
                $order->type_payment = \common\components\payments\ClaPayment::TYPE_PAYMENT;
            }
            $order->save(false);
            if ($product) {
                $oitem =  new \common\models\order\OrderItem();
                $oitem->product_id = $product->id;
                $oitem->order_id = $order->id;
                $oitem->shop_id = $order->shop_id;
                $oitem->code = $product->code;
                $oitem->price = $product->getPrice(1);
                $tg = $product->getPriceC1(1);
                $tg = $product->getPriceC1(1) > 0 ? $tg : 1000;
                $oitem->quantity = \common\models\gcacoin\Gcacoin::getMoneyToCoin($coin_pay) / $tg;
                $oitem->avatar_path = $product->avatar_path;
                $oitem->avatar_name = $product->avatar_name;
                $oitem->getAffiliate();
                $oitem->name = '[QR dịch vụ]' . $product->name;
                // echo $oitem->quantity; die();
                if (!$oitem->save()) {
                    print_r($oitem->getErrors());
                    die;
                }
                if ($order->costAffiliateService([$oitem])) {
                    if ($oitem->sale > 0) {
                        $user_id = $order->user_id;
                        $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
                        $first_coin = $gca_coin->getCoin();
                        $coin = $oitem->sale;
                        if ($gca_coin->addCoin($coin) && $gca_coin->save(false)) {
                            $history = new \common\models\gcacoin\GcaCoinHistory();
                            $history->user_id = $user_id;
                            $history->type = 'RESALE_AFFILIATE';
                            $history->data = 'Hoàn ' . __VOUCHER . ' khuyến mãi sản phẩm dịch vụ.';
                            $history->gca_coin = $coin;
                            $history->first_coin = $first_coin;
                            $history->last_coin = $gca_coin->getCoin();
                            $history->save(false);
                        } else {
                            $siteinfo = \common\components\ClaLid::getSiteinfo();
                            $email_manager = $siteinfo->email;
                            if ($email_manager) {
                                \common\models\mail\SendEmail::sendMail([
                                    'email' => $email_manager,
                                    'title' => 'Thêm tiền lỗi',
                                    'content' => json_encode([$user_id => $it])
                                ]);
                            }
                        }
                    }
                    $order->addAffiliate();
                    $notify['title'] = 'Thanh toán QR dịch vụ thành công.';
                    $notify['description'] = 'Mã đơn hàng QR dịch vụ ' . $order->getOrderLabelId() . ' giá trị ' . formatCoin($coin_pay) . ' ' . __VOUCHER . ' đã được thanh toán thành công.';
                    $notify['link'] = \common\components\ClaUrl::to(['/management/order/index']);
                    $notify['type'] = \common\models\notifications\Notifications::ORDER;
                    $notify['recipient_id'] = $product['shop_id'];
                    \common\models\notifications\Notifications::pushMessage($notify);
                } else {
                    $order->status_check_cancer = \common\components\ClaLid::STATUS_ACTIVED;
                    $order->time_check_cancer = time();
                    $order->save(false);
                }
            }
        }
        return true;
    }
}

<?php

namespace common\models\order;

use Yii;
use common\components\ClaLid;
use common\components\payments\ClaPayment;

/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property string $key
 * @property string $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $district_id
 * @property string $province_id
 * @property string $shipping_name
 * @property string $shipping_email
 * @property string $shipping_phone
 * @property string $shipping_address
 * @property string $shipping_district_id
 * @property string $shipping_province_id
 * @property string $order_total
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_delivery
 * @property integer $received_money
 * @property integer $shipfee
 * @property integer $point_accrued
 */
class Order extends \common\models\ActiveRecordC
{

    const ORDER_WAITING_PROCESS = 1; // Đơn hàng mới
    const ORDER_WAITING_IMPORT_PRODUCT = 2; // Đơn hàng đang chờ lấy hàng
    const ORDER_WAITING_ADD_MORE = 3; // Đơn hàng đang giao
    const ORDER_DELIVERING = 4; // Đơn hàng đã giao
    const ORDER_CANCEL = 0; // Đơn hàng hủy
    const PAYMENT_STATUS_NO = 0; // Chưa thanh toán
    const PAYMENT_STATUS_YES = 1; // Đã thanh toán
    const ORDER_PROCESSING = 6; // Đang xử lý
    const ORDER_COD_DELIVERING = 7; // COD đang giao
    const ORDER_DELIVERY_SUCCESS = 8; // Giao thành công

    public $_error_affiliate = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'address'], 'required'],
            [['user_id', 'district_id', 'province_id', 'shipping_district_id', 'shipping_province_id', 'created_at', 'updated_at', 'payment_status', 'delivery_status', 'status', 'bank_transfer', 'money_customer_transfer', 'confirm_customer_transfer', 'shipfee', 'point_accrued'], 'integer'],
            [['name', 'email', 'address', 'shipping_name', 'shipping_email', 'shipping_address', 'facebook', 'key'], 'string', 'max' => 255],
            [['order_total'], 'number'],
            [['email', 'shipping_email'], 'email'],
            [['phone', 'shipping_phone'], 'integer'],
            [['phone', 'shipping_phone'], 'string', 'min' => 9, 'max' => 13],
            [['note'], 'string', 'max' => 500],
            [['user_delivery', 'received_money', 'shipfee', 'currency', 'delivery_status', 'payment_status', 'payment_method', 'payment_method_child', 'user_address_id', 'shop_address_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => Yii::t('app', 'full_name'),
            'email' => 'Email',
            'phone' => Yii::t('app', 'phone'),
            'address' => Yii::t('app', 'address'),
            'district_id' => Yii::t('app', 'district'),
            'province_id' => Yii::t('app', 'province'),
            'order_total' => Yii::t('app', 'order_total'),
            'note' => Yii::t('app', 'content'),
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
            'payment_status' => Yii::t('app', 'payment_status'),
            'delivery_status' => Yii::t('app', 'delivery_status'),
            'status' => Yii::t('app', 'status'),
            'bank_transfer' => Yii::t('app', 'bank_transfer'),
            'facebook' => Yii::t('app', 'facebook'),
            'money_customer_transfer' => Yii::t('app', 'money_customer_transfer'),
            'confirm_customer_transfer' => Yii::t('app', 'confirm_customer_transfer'),
            'user_delivery' => Yii::t('app', 'user_delivery'),
            'received_money' => Yii::t('app', 'received_money'),
            'shipfee' => Yii::t('app', 'shipfee'),
            'shipping_name' => Yii::t('app', 'full_name'),
            'shipping_email' => 'Email',
            'shipping_phone' => Yii::t('app', 'phone'),
            'shipping_address' => Yii::t('app', 'address'),
            'shipping_district_id' => Yii::t('app', 'district'),
            'shipping_province_id' => Yii::t('app', 'province'),
            'payment_method' => Yii::t('app', 'payment_method'),
            'payment_status' => 'Trạng thái thanh toán',
            'order_total' => 'Tống hóa đơn',
            'point_accrued' => 'Đã tích lũy điểm'
        ];
    }

    /**
     * Trạng thái thanh toán
     * @return type
     */
    public static function arrayPaymentStatus()
    {
        return [
            ClaPayment::PAYMENT_STATUS_SUCCESS => 'Đã thanh toán',
            ClaPayment::PAYMENT_STATUS_WAITING => 'Đang chờ thanh toán',
            ClaPayment::PAYMENT_STATUS_CANCEL => 'Chưa thanh toán',
        ];
    }

    public static function getPaymentStatusName($status)
    {
        $data = self::arrayPaymentStatus();
        return isset($data[$status]) ? $data[$status] : '';
    }

    /**
     * Trạng thái giao hàng
     * @return type
     */
    public static function arrayDeliveryStatus()
    {
        return [
            ClaLid::STATUS_ACTIVED => 'Đã giao hàng',
            ClaLid::STATUS_DEACTIVED => 'Chưa giao hàng'
        ];
    }

    public static function arrayStatus($id = -100)
    {
        $data = [
            self::ORDER_WAITING_PROCESS => Yii::t('app', 'new_order'),
            self::ORDER_WAITING_IMPORT_PRODUCT => Yii::t('app', 'order_waiting_add'),
            self::ORDER_WAITING_ADD_MORE => Yii::t('app', 'order_waiting_transport'),
            self::ORDER_DELIVERING => Yii::t('app', 'order_recived'),
            self::ORDER_CANCEL => Yii::t('app', 'order_canced'),
        ];

        return (isset($data[$id])) ? $data[$id] : $data;
    }

    public static function getNameStatus($status)
    {
        $data = self::arrayStatus();
        return isset($data[$status]) ? $data[$status] : '';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
                \common\models\NotificationAdmin::addNotifcation('order');
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Đơn hàng chờ xử lý và đã đặt cọc
     */
    public static function getOrdersWaitingOrder()
    {
        $condition = 'status=:status AND confirm_customer_transfer=:confirm_customer_transfer';
        $params = [
            ':status' => self::ORDER_WAITING_PROCESS,
            ':confirm_customer_transfer' => ClaLid::STATUS_ACTIVED
        ];
        $data = (new \yii\db\Query())->select('*')
            ->from('order')
            ->where($condition, $params)
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

    /**
     * Lấy ra các đơn hàng chờ xử lý
     * @return type
     */
    public static function getOrdersWaitingProcess()
    {
        $condition = 'status=:status';
        $params = [':status' => self::ORDER_WAITING_PROCESS];
        $data = (new \yii\db\Query())->select('*')
            ->from('order')
            ->where($condition, $params)
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

    public static function getOrderItemsByOrderId($order_id)
    {
        $data = (new \yii\db\Query())->select('*')
            ->from('order_item')
            ->where('order_id=:order_id', [':order_id' => $order_id])
            ->all();
        return $data;
    }

    public static function getOrderItems($orders)
    {
        $order_ids = array_column($orders, 'id');
        $data = (new \yii\db\Query())->select('*')
            ->from('order_item')
            ->where('order_id IN (' . implode(',', $order_ids) . ')')
            ->all();
        return $data;
    }

    public static function arrayBankTransfer()
    {
        return [
            1 => 'Techcombank',
            2 => 'Vietcombank',
            3 => 'Agribank',
            4 => 'BIDV',
            5 => 'MB Bank',
        ];
    }

    public static function getNameBankTransfer($id)
    {
        $data = self::arrayBankTransfer();
        return (isset($data[$id]) && $data[$id]) ? $data[$id] : '';
    }

    public static function getProductsInOrder($id)
    {
        $data = (new \yii\db\Query())->select('t.*, r.*')
            ->from('order_item r')
            ->join('LEFT JOIN', 'product t', 't.id = r.product_id')
            ->where('r.order_id=:order_id', [':order_id' => $id])
            ->orderBy('r.id DESC')
            ->all();
        return $data;
    }

    public static function checkOutOfStock($id)
    {
        $data = (new \yii\db\Query())->select('*')
            ->from('order_item')
            ->where('order_id=:order_id AND status=2', [':order_id' => $id])
            ->count();
        return $data;
    }

    public static function checkSuccess($id)
    {
        $success = 1;
        $data = (new \yii\db\Query())->select('*')
            ->from('order_item')
            ->where('order_id=:order_id', [':order_id' => $id])
            ->all();
        if ($data) {
            foreach ($data as $item) {
                if ($item['status'] != 3) {
                    $success = 0;
                    break;
                }
            }
        } else {
            $success = 0;
        }
        return $success;
    }

    public static function getOrderNote($order_id)
    {
        $data = (new \yii\db\Query())->select('*')
            ->from('order_note')
            ->where('order_id=:order_id', [':order_id' => $order_id])
            ->all();
        return $data;
    }

    public static function getLatestOrderNote($order_id)
    {
        $row = (new \yii\db\Query())->select('*')
            ->from('order_note')
            ->where('order_id=:order_id', [':order_id' => $order_id])
            ->orderBy('created_at DESC')
            ->one();
        return $row;
    }

    public static function updateOrder($id)
    {
        $list = \common\models\order\OrderShop::find()->where(['order_id' => $id])->all();
        $min = -100;
        if ($list) {
            foreach ($list as $os) {
                if ($os['status'] > $min) {
                    $min = $os['status'];
                }
            }

            $order = self::findOne($id);
            if ($order && $order->status != $min) {
                $old = $order->status;
                $order->status = $min;
                $order->save();
                $log = new \common\models\order\OrderLog();
                $log->order_id = $id;
                $log->user_id = 0;
                $log->content = '{"status":{"old": ' . $old . ',"new":' . $order->status . '}}';
                $log->created_at = time();
                $log->save();
            }
        }
    }

    public static function checkPickMoney($id)
    {
        $order = self::findOne($id);
        if ($order && $order->payment_method == 2) {
            return 1;
        }
        return 0;
    }

    public static function getOrderStatusName($order_status)
    {
        $return = '';
        if ($order_status == Order::ORDER_DELIVERING) {
            $return = 'Thành công';
        } else if ($order_status == Order::ORDER_CANCEL) {
            $return = 'Hủy';
        } else {
            $return = 'Chờ duyệt';
        }
        return $return;
    }

    function getBankname()
    {
        $model = \common\models\BankAdmin::findOne($this->payment_method_child);
        if ($model) {
            return $model->bank_name . '/' . $model->user_name;
        }
        return $this->payment_method_child;
    }

    function paymentV()
    {
        $gcoin = \common\models\gcacoin\Gcacoin::getModel($this->user_id);
        $first_coin = $this->getCoinTo($gcoin);
        if ($this->paymentFor($gcoin)) {
            if (\common\models\gcacoin\CoinConfinement::addByOrder($this)) {
                $oid = $this->getOrderLabelId();
                $history = new \common\models\gcacoin\GcaCoinHistory();
                $history->user_id = $gcoin->user_id;
                $history->type = 'PAY_MEMBER_VS';
                $history->data = 'Thanh toán đơn hàng thành công đơn hàng ' . $oid;
                $history->gca_coin = -$this->getCoinTotalPay();
                $history->first_coin = $first_coin;
                $history->last_coin = $this->getCoinTo($gcoin);
                $history->type_coin = $this->getTypeCoin();
                $history->save(false);
                $_v_f = $this->getTextVByCoin($history->gca_coin);
                $_v_l = $this->getTextVByCoin($history->last_coin);
                //sen mail
                $user = \frontend\models\User::findOne($gcoin->user_id);
                if ($user && $user->email) {
                    \common\models\mail\SendEmail::sendMail([
                        'email' => $user->email,
                        'title' => 'Thanh toán thành công đơn hàng ' . $oid,
                        'content' => 'Số dư thay đổi <b style="color: green"> ' . $_v_f . '</b>.  Số dư hiện tại: <b style="color: green">' . $_v_l . '</b>'
                    ]);
                }
                //send notification
                $noti = new \common\models\notifications\Notifications();
                $code = 'Mã đơn hàng <b style="color: green"> ' . $oid . '</b>';
                $noti->title = 'Thanh toán đơn hàng thành công.';
                $noti->description = 'Thanh toán đơn hàng thành công. ' . $code . '. Số dư thay đổi <b style="color: green">' . $_v_f . '</b>.  Số dư hiện tại: <b style="color: green">' . $_v_l . '</b>';
                $noti->link = \common\components\ClaUrl::to(['/management/gcacoin/index']);
                $noti->type = 3;
                $noti->recipient_id = $gcoin->user_id;
                $noti->unread = \common\components\ClaLid::STATUS_ACTIVED;
                $noti->save();
                return $history->id;
            } else {
                $gcoin->addMoney($this_total);
                $noti = new \common\models\notifications\Notifications();
                $code = 'Mã đơn hàng <b style="color: green"> ' . $this->getOrderLabelId() . '</b>';
                $noti->title = 'Thanh toán đơn hàng không thành công.';
                $noti->description = 'Thanh toán đơn không hàng thành công.' . $code . '. Xem lý do.';
                $noti->link = \common\components\ClaUrl::to(['/management/gcacoin/index']);
                $noti->type = 3;
                $noti->recipient_id = $gcoin->user_id;
                $noti->unread = \common\components\ClaLid::STATUS_ACTIVED;
                $noti->save();
                return 0;
            }
        }
    }

    function getTypeCoin()
    {
        if ($this->isVSale()) {
            return \common\models\gcacoin\GcaCoinHistory::TYPE_V_SALE;
        }
        return \common\models\gcacoin\GcaCoinHistory::TYPE_V;
    }

    function paymentFor($gcoin)
    {
        $coin = $this->getCoinTotalPay();
        if ($this->isVSale()) {
            return ($gcoin->addCoinSale(-$coin) > 0 && $gcoin->save(false));
        }
        return ($gcoin->addCoin(-$coin) > 0 && $gcoin->save(false));
    }

    function getCoinTotalPay()
    {
        $t = $this->order_total - $this->shipfee;
        if (!$this->isVSale()) {
            $t =  $t - $this->getMoneySaleBuyCoin();
        }
        return \common\models\gcacoin\Gcacoin::getCoinToMoney($t);
    }

    function getCoinTo($gcoin)
    {
        if ($this->isVSale()) {
            return $gcoin->getCoinSale();
        }
        return $gcoin->getCoin();
    }

    function getTextV($order_total = null)
    {
        $order_total = ($order_total !== null) ? $order_total : $this->order_total;
        $v = \common\models\gcacoin\Gcacoin::getCoinToMoney($order_total);
        if ($this->isVSale() == 1) {
            return formatMoney($v + ($v * $this->percent_sale / 100)) . ' ' . __VOUCHER_SALE;
        }
        return formatMoney($v) . ' ' . __VOUCHER;
    }

    function getTextVByCoin($coin)
    {
        if ($this->isVSale() == 1) {
            return formatMoney($coin) . ' ' . __VOUCHER_SALE;
        }
        return formatMoney($coin) . ' ' . __VOUCHER;
    }

    public function getShopAddressSlected($id_selected = false)
    {
        $shop_id = $this->shop_id;
        if (!$shop_id) {
            $this->shop_adress_id = 0;
            return false;
        }
        $location_shop = \common\models\shop\ShopAddress::getByShop($shop_id);
        if ($id_selected === false) {
            $id_selected = \common\components\ClaCookie::getValueCookieShopAddress($this->shop_id);
        }
        if (!($id_selected === false)) {
            foreach ($location_shop as $item) {
                if ($id_selected == $item['id']) {
                    $this->shop_adress_id = $id_selected;
                    return true;
                }
            }
        } else {
            foreach ($location_shop as $item) {
                if ($item['isdefault']) {
                    $this->shop_adress_id = $item['id'];
                    return true;
                }
            }
        }
        $this->shop_adress_id = 0;
        return false;
    }

    public static function getInShopByStatus($shop_id, $status, $options = [])
    {
        $query = (new \yii\db\Query())->select('o.*, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, s.name as s_name, s.alias as s_alias')
            ->from('order o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            ->where(['o.shop_id' => $shop_id, 'o.status' => $status])
            ->andWhere(['type_payment' => 0]);
        if (isset($options['count']) && $options['count']) {
            return $query->count();
        }
        return $query->orderBy('id DESC')->all();
    }

    public static function getByUserByStatus($id, $status, $options = [])
    {
        $limit = isset($options['limit']) && $options['limit'] ? $options['limit'] : 100;
        $page = isset($options['page']) && $options['page'] ? $options['page'] : 1;
        $offset = ($page - 1) * $limit;
        $query = (new \yii\db\Query())->select('o.*, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, s.name as s_name, s.alias as s_alias')
            ->from('order o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            ->where(['o.user_id' => $id, 'o.status' => $status])
            ->andWhere(['type_payment' => 0]);
        if (isset($options['count']) && $options['count']) {
            return $query->count();
        }
        return $query->limit($limit)->offset($offset)->orderBy('id DESC')->all();
    }

    public static function getByShopByStatus($id, $status, $options = [])
    {
        $limit = isset($options['limit']) && $options['limit'] ? $options['limit'] : 100;
        $page = isset($options['page']) && $options['page'] ? $options['page'] : 1;
        $offset = ($page - 1) * $limit;
        $query = (new \yii\db\Query())->select('o.*, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, s.name as s_name, s.alias as s_alias')
            ->from('order o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            ->where(['o.shop_id' => $id, 'o.status' => $status])
            ->andWhere(['type_payment' => 0]);
        if (isset($options['count']) && $options['count']) {
            return $query->count();
        }
        return $query->limit($limit)->offset($offset)->orderBy('id DESC')->all();
    }


    public static function getDetail($id)
    {
        $data = (new \yii\db\Query())->select('o.*, s.phone as shop_phone, s.name_contact, o.phone as user_phone')
            ->from('order o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            ->where(['o.id' => $id])
            ->one();
        return $data;
    }

    function getOrderLabelId()
    {
        return 'OR' . $this->id;
    }

    function isVSale()
    {
        return ($this->to_sale == 1);
    }

    public static function getInShopById($id)
    {
        return Order::find()->where(['id' => $id, 'shop_id' => Yii::$app->user->id])->one();
    }

    public static function getInUserById($id)
    {
        return Order::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();
    }

    public function getCoinSalebyShop()
    {
        $order_total = \common\models\gcacoin\Gcacoin::getCoinToMoney($this->order_total);
        if ($order_total > 0) {
            return $order_total - $this->getSaleAffBuyCoin();
        }
        return $order_total;
    }

    public function getSaleAffBuyCoin()
    {
        $sum = (new \yii\db\Query())->from('order_item')->select(" SUM(sale_buy_shop_coin) as sum ")->where(['order_id' => $this->id])->all();
        if ($sum && isset($sum[0]['sum'])) {
            return $sum[0]['sum'];
        }
        return 0;
    }

    // Affiliate
    public function getSaleBuyCoin()
    {
        // return 0;
        $sum = (new \yii\db\Query())->from('order_item')->select(" SUM(sale_buy_shop_coin) as sum ")->where(['order_id' => $this->id])->all();
        if ($sum && isset($sum[0]['sum'])) {
            return $sum[0]['sum'];
        }
        return 0;
    }

    public function getMoneySaleBuyCoin()
    {
        // return 0;
        $sum = (new \yii\db\Query())->from('order_item')->select(" SUM(sale_buy_shop_coin) as sum ")->where(['order_id' => $this->id])->all();
        if ($sum && isset($sum[0]['sum'])) {
            return \common\models\gcacoin\Gcacoin::getMoneyToCoin($sum[0]['sum']);
        }
        return 0;
    }

    function getAddress($add = null)
    {
        $add = $add ? $add : \common\models\user\UserAddress::getDefaultAddressByUserId($this->user_id);
        if ($add) {
            $this->user_address_id = $add['id'];
            $this->district_id = $add['district_id'];
            $this->province_id = $add['province_id'];
            $this->address = $add['address'] . '( ' . $add['ward_name'] . ', ' . ($add['district_name']) . ', ' . $add['province_name'] . ')';
            $this->name = $add['name_contact'];
            $this->phone = $add['phone'];
            $this->email = trim($add['email']);
        }
    }

    public function checkCostAffilate($data)
    {
        if ($data) {
            foreach ($data as $shop_id => $order_items) {
                $coin = 0;
                foreach ($order_items as $it) {
                    $ot = new OrderItem();
                    $ot->shop_id = $shop_id;
                    $ot->product_id = $it['id'];
                    $ot->quantity = $it['quantity'];
                    if ($ot->getAffiliate($this->user_id)) {
                        if ($ot->user_before_product > 0 && $ot->sale_before_product > 0) {
                            $coin += $ot->sale_before_product;
                        }
                        if ($ot->user_before_shop > 0 && $ot->sale_before_shop > 0) {
                            $coin += $ot->sale_before_shop;
                        }
                        if ($ot->sale_admin > 0) {
                            $coin += $ot->sale_admin;
                        }
                    } else {
                        $this->_error_affiliate = $ot->_merrors;
                        return false;
                    }
                }
                if ($coin > 0) {
                    $gca_coin = \common\models\gcacoin\Gcacoin::getModel($shop_id);
                    if (!$gca_coin->addCoin(-$coin)) {
                        $shop = \common\models\shop\Shop::findOne($shop_id);
                        if ($shop) {
                            $this->_error_affiliate = 'Doanh nghiệp ' . $shop->name . ' không thỏa mãn yêu cầu bán hàng. Quý khách vui loại bỏ sản phẩm của doang nghiệp này để tiếp tục mua hàng';
                            $email = $shop->email;
                            if ($email) {
                                \common\models\mail\SendEmail::sendMail([
                                    'email' => $email,
                                    'title' => 'Không thể hoàn thành đặt đơn hàng affilate.',
                                    'content' => 'Không thể hoàn thành đơn hàng affilate do số tiền trong tài khoản của quý khách không đủ. Đơn hàng đã bị xóa bỏ. Vui lòng nạp tiền vào tài khoản để đảm bảo đơn hàng của bạn được đặt thành công',
                                ]);
                            }
                        } else {
                            $this->_error_affiliate = 'Có sản phẩm đã hết hạn mua hàng. Quý khách vui lòng xóa toàn bộ sản phẩm trong giỏ hàng và chọn lại';
                        }
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function getErrorAffiliate()
    {
        return $this->_error_affiliate;
    }

    public function costAffiliateService($order_items = [])
    {
        $coin = $this->getCostAffiliateService($order_items);
        if ($coin > 0) {
            $user_id = $this->shop_id;
            $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
            $first_coin = $gca_coin->getCoin();
            if (!$gca_coin->addCoin(-$coin)) {
                $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
                $user = \frontend\models\User::findIdentity($user_id);
                if ($user->transferVr($coin)) {
                    $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
                } else {
                    $this->_error_affiliate = 'Doanh nghiệp không đủ điều kiện ' . __VOUCHER . ' tham gia Affilliate.';
                    return false;
                }
            } else {
                $gca_coin->addCoin($coin);
            }
            if ($gca_coin->addCoin(-$coin) && $gca_coin->save(false)) {
                $history = new \common\models\gcacoin\GcaCoinHistory();
                $history->user_id = $user_id;
                $history->type = 'COST_AFFILIATE';
                $history->data = 'Thanh toán phí affiliate đơn hàng ' . $this->getOrderLabelId();
                $history->gca_coin = -$coin;
                $history->first_coin = $first_coin;
                $history->last_coin = $gca_coin->getCoin();
                $history->save(false);
                return true;
            } else {
                $this->_error_affiliate = 'Doanh nghiệp không đủ điều kiện ' . __VOUCHER . ' tham gia Affilliate.';
                return false;
            }
        }
        return true;
    }

    public function costAffiliate($order_items = [])
    {
        $coin = $this->getCostAffiliate($order_items);
        if ($coin > 0) {
            $user_id = $this->shop_id;
            $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
            $first_coin = $gca_coin->getCoin();
            if ($gca_coin->addCoin(-$coin) && $gca_coin->save(false)) {
                $history = new \common\models\gcacoin\GcaCoinHistory();
                $history->user_id = $user_id;
                $history->type = 'COST_AFFILIATE';
                $history->data = 'Thanh toán phí affiliate đơn hàng ' . $this->getOrderLabelId();
                $history->gca_coin = -$coin;
                $history->first_coin = $first_coin;
                $history->last_coin = $gca_coin->getCoin();
                $history->save(false);
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function getCostAffiliateService($order_items = [])
    {
        $order_items = $order_items ? $order_items : \common\models\order\OrderItem::find()->where(['order_id' => $this->id])->all();
        $coin = 0;
        if ($order_items) {
            foreach ($order_items as $ot) {
                if ($ot->user_before_product > 0 && $ot->sale_before_product > 0) {
                    $coin += $ot->sale_before_product;
                }
                if ($ot->user_before_shop > 0 && $ot->sale_before_shop > 0) {
                    $coin += $ot->sale_before_shop;
                }
                if ($ot->sale_charity > 0) {
                    $coin += $ot->sale_charity;
                }
                if ($ot->sale_admin > 0) {
                    $coin += $ot->sale_admin;
                }

                if ($ot->sale > 0) {
                    $coin += $ot->sale;
                }
            }
        }
        return $coin;
    }

    public function getCostAffiliate($order_items = [])
    {
        $order_items = $order_items ? $order_items : \common\models\order\OrderItem::find()->where(['order_id' => $this->id])->all();
        $coin = 0;
        if ($order_items) {
            foreach ($order_items as $ot) {
                if ($ot->user_before_product > 0 && $ot->sale_before_product > 0) {
                    $coin += $ot->sale_before_product;
                }
                if ($ot->user_before_shop > 0 && $ot->sale_before_shop > 0) {
                    $coin += $ot->sale_before_shop;
                }
                if ($ot->sale_charity > 0) {
                    $coin += $ot->sale_charity;
                }
                if ($ot->sale_admin > 0) {
                    $coin += $ot->sale_admin;
                }
            }
        }
        return $coin;
    }

    public function recostAffiliate($order_items = [])
    {
        $coin = $this->getCostAffiliate($order_items);
        // echo  $coin; die();
        if ($coin > 0) {
            $user_id = $this->shop_id;
            $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
            $first_coin = $gca_coin->getCoin();
            if ($gca_coin->addCoin($coin) && $gca_coin->save(false)) {
                $history = new \common\models\gcacoin\GcaCoinHistory();
                $history->user_id = $user_id;
                $history->type = 'RECOST_AFFILIATE';
                $history->data = 'Hoàn phí affiliate đơn hàng ' . $this->getOrderLabelId();
                $history->gca_coin = $coin;
                $history->first_coin = $first_coin;
                $history->last_coin = $gca_coin->getCoin();
                $history->save(false);
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function addAffiliate()
    {
        if ($this->status_check_cancer != \common\components\ClaLid::STATUS_ACTIVED) {
            $this->status_check_cancer = \common\components\ClaLid::STATUS_ACTIVED;
            $this->time_check_cancer = time();
            $addmoney = [];
            if ($this->save(false)) {
                $order_items = \common\models\order\OrderItem::find()->where(['order_id' => $this->id])->all();
                if ($order_items) {
                    foreach ($order_items as $ot) {
                        if ($ot->user_before_product > 0 && $ot->sale_before_product > 0) {
                            if (!isset($addmoney[$ot->user_before_product])) {
                                $addmoney[$ot->user_before_product]['coin'] = 0;
                                $addmoney[$ot->user_before_product]['note'] = '';
                            }
                            $addmoney[$ot->user_before_product]['coin'] += $ot->sale_before_product;
                            $addmoney[$ot->user_before_product]['note'] .= 'Thưởng giới thiệu sản phẩm ID' . $ot->product_id . ' ' . $ot->sale_before_product . ' ' . __VOUCHER . ' trong đơn hàng ' . $this->getOrderLabelId() . '. ';
                        }
                        if ($ot->user_before_shop > 0 && $ot->sale_before_shop > 0) {
                            if (!isset($addmoney[$ot->user_before_shop])) {
                                $addmoney[$ot->user_before_shop]['coin'] = 0;
                                $addmoney[$ot->user_before_shop]['note'] = '';
                            }
                            $addmoney[$ot->user_before_shop]['coin'] += $ot->sale_before_shop;
                            $addmoney[$ot->user_before_shop]['note'] .= 'Thưởng giới thiệu gian hàng ID' . $ot->shop_id . ' ' . $ot->sale_before_shop . ' ' . __VOUCHER . ' trong đơn hàng ' . $this->getOrderLabelId() . '. ';
                        }
                        if ($ot->sale_admin > 0) {
                            $coin = $ot->sale_admin;
                            $text = 'Nhận thành công ' . __VOUCHER . ' từ Affilliate doanh nghiệp ID' . $this->shop_id;
                            \common\models\gcacoin\Gcacoin::addCoinAdmin($coin, ['note' => $text, 'type' => 'APPQR_ADD_AFFILIATE']);
                        }
                        if ($ot->sale_charity > 0) {
                            $coin = $ot->sale_charity;
                            $text = 'Nhận thành công ' . __VOUCHER . ' từ Affilliate doanh nghiệp ID' . $this->shop_id;
                            \common\models\gcacoin\Gcacoin::addCoinCharity($coin, ['note' => $text, 'type' => 'APPQR_ADD_AFFILIATE']);
                        }
                    }
                }
            }
            if ($addmoney) {
                foreach ($addmoney as $user_id => $it) {
                    $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
                    $first_coin = $gca_coin->getCoin();
                    if ($gca_coin->addCoin($it['coin']) && $gca_coin->save(false)) {
                        $history = new \common\models\gcacoin\GcaCoinHistory();
                        $history->user_id = $user_id;
                        $history->type = 'PAY_MEMBER_ADD_AFFILIATE';
                        $history->data = $it['note'] . ' Tổng: ' . $it['coin'] . ' ' . __VOUCHER;
                        $history->gca_coin = $it['coin'];
                        $history->first_coin = $first_coin;
                        $history->last_coin = $gca_coin->getCoin();
                        $history->type_coin = 0;
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
            }
        }
    }

    public static function getAllAddMoneyOther()
    {
        $hour_confinement_config = \common\models\gcacoin\Config::getHourConfinement();
        $hour_confinement = time() - $hour_confinement_config;
        return self::find()->where(['status' => \common\models\order\Order::ORDER_DELIVERING, 'status_check_cancer' => \common\components\ClaLid::STATUS_DEACTIVED])->andWhere(" updated_at <= '$hour_confinement' ")->limit(15)->all();
    }
    // Affiliate

    //stranport
    function getFeeTransport()
    {
        if ($this->transport_type) {
            $free_rp = \frontend\components\Transport::getInfoTransport($this);
            if ($free_rp && isset($free_rp['order']) && $free_rp['order']) {
                $this->shipfee = $free_rp['fee'];
                $this->transport_id = $free_rp['order'];
                $this->order_total += $this->shipfee;
            } else {
                $this->transport_type = 0;
            }
            $this->save();
        }
    }

    public static function updateStatusAuto($order_shop_id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $order_shop = self::findOne($order_shop_id);
        $tru = false;
        if ($order_shop && $order_shop->status != 0) {
            if ($order_shop->status > 1) {
                $tru = true;
            }
            $history = \common\models\order\OrderShopHistory::getLast($order_shop_id);
            $claShipping = new \common\components\shipping\ClaShipping();
            $claShipping->loadVendor(['method' => $order_shop->transport_type]);
            $options['data']['OrderCode'] = $order_shop->transport_id;
            $info = $claShipping->getInfoOrderUpdate($options);
            // $info['time'] = '22-06-2018';
            if ($info['time'] && $info['status']) {
                $time = strtotime($info['time']);
                $status = \common\models\order\OrderShopHistory::getStatusId($info['status'], $order_shop->transport_type);
                if ($status != $history['status']) {
                    $data = [
                        'order_id' => $order_shop_id,
                        'time' => $time,
                        'status' => $status,
                        'type' => $order_shop->transport_type,
                        'content' => Yii::t('app', 'update_auto_ghn'),
                        'created_at' => time()
                    ];
                    $statustg = \common\models\order\OrderShopHistory::getSystemStatus($status, $order_shop->transport_type);
                    if ($statustg != $order_shop->status) {
                        $order_shop->status = $statustg;
                        if ($order_shop->save()) {
                            if ($order_shop->status == 2) {
                                $order_shop->setPromotion();
                            }
                            if ($order_shop->status == 0 && $tru) {
                                if (\common\models\gcacoin\CoinConfinement::cancerShopOrder($order_shop)) {
                                    $order_shop->setPromotion(0);
                                }
                            }
                            $sve = \common\models\notifications\Notifications::updateStatusOrder($order_shop);
                            return \common\models\order\OrderShopHistory::saveData($data);
                        }
                    } else {
                        return \common\models\order\OrderShopHistory::saveData($data);
                    }
                }
            }
            return false;
        }
    }

    public static function findByTransportId($transport_id)
    {
        return self::find()->where(['transport_id' => $transport_id])->one();
    }
    //stranport

    public function beforeDelete()
    {
        if (($this->transport_id || $this->payment_status == ClaPayment::PAYMENT_STATUS_SUCCESS) && $this->status != 0) {
            return false;
        }
        return parent::beforeDelete();
    }

    public function setPromotion($booleen = 1)
    {
        if ($this->id) {
            $items = \common\models\order\OrderItem::find()->where(['order_id' => $this->id])->all();
            if ($items) {
                foreach ($items as $item) {
                    \common\models\promotion\ProductToPromotions::setQuantitySelled($item->product_id, ($booleen ? $item->quantity : (-$item->quantity)), $item->price);
                }
            }
        }
    }

    public static function getOrderByKey($key, $time = null)
    {
        return self::find()->where(['key' => $key])->all();
    }

    public static function countOrder($order_status, $user_id, $options = [])
    {
        $condition = "ot.user_before_product=$user_id AND t.status IN (" . implode(',', $order_status) . ")";
        if (isset($options['start_date']) && $options['start_date']) {
            $condition .= ' AND t.created_at >= :start_date';
            $start_date_string = $options['start_date'] . ' 00:00:00';
            $start_date = strtotime($start_date_string);
            $params[':start_date'] = $start_date;
        }
        if (isset($options['end_date']) && $options['end_date']) {
            $condition .= ' AND t.created_at <= :end_date';
            $end_date_string = $options['end_date'] . ' 23:59:59';
            $end_date = strtotime($end_date_string);
            $params[':end_date'] = $end_date;
        }
        if (isset($options['affiliate_id']) && $options['affiliate_id']) {
            $condition .= ' AND t.affiliate_id=:affiliate_id';
            $params[':affiliate_id'] = $options['affiliate_id'];
        }
        $count = (new \yii\db\Query())->select('COUNT(*) as count')
            ->from('order_item AS ot')
            ->rightJoin('order AS t', 't.id=ot.order_id')
            ->groupBy('t.id')
            ->where($condition, $params)
            ->count();
        return $count;
    }

    public static function getAllOrder($user_id, $options = [])
    {
        $condition = 'c.object_type =1 AND t.user_before_product=:user_id';
        $params = [
            ':user_id' => $user_id
        ];
        if (isset($options['start_date']) && $options['start_date']) {
            $condition .= ' AND t.created_at >= :start_date';
            $start_date_string = $options['start_date'] . ' 00:00:00';
            $start_date = strtotime($start_date_string);
            $params[':start_date'] = $start_date;
        }
        if (isset($options['end_date']) && $options['end_date']) {
            $condition .= ' AND t.created_at <= :end_date';
            $end_date_string = $options['end_date'] . ' 23:59:59';
            $end_date = strtotime($end_date_string);
            $params[':end_date'] = $end_date;
        }
        $data = (new \yii\db\Query())
            ->select('t.*, c.operating_system, r.campaign_source, r.aff_type, r.campaign_name')
            ->from('order_item t')
            ->distinct()
            ->leftJoin('affiliate_click c', 'c.object_id = t.product_id')
            ->leftJoin('affiliate_link r', 'r.user_id = t.user_before_product')
            ->where($condition, $params)
            ->all();
        return $data;
    }

    function paymentAPI()
    {
        switch ($this->payment_method) {
            case \common\components\payments\ClaPayment::PAYMENT_METHOD_MEMBERIN:
                $orders = \common\models\order\Order::getOrderByKey($this->key);
                if ($orders) {
                    foreach ($orders as $order) {
                        if ($order->paymentV()) {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                            $order->save(false);
                        } else {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_WAITING;
                            $order->save(false);
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            case \common\components\payments\ClaPayment::PAYMENT_METHOD_MEMBERVS:
                $orders = \common\models\order\Order::getOrderByKey($this->key);
                if ($orders) {
                    foreach ($orders as $order) {
                        if ($order->paymentV()) {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                            $order->save(false);
                        } else {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_WAITING;
                            $order->save(false);
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            case \common\components\payments\ClaPayment::PAYMENT_METHOD_CK:
                return false;
        }
        return false;
    }

    function addDiscountCode($code)
    {
        if ($code) {
            $products = OrderItem::getByShopOrder($this->id);
            $ids = array_column($products, 'product_id');
            $model = \common\models\product\DiscountCode::checkCodeOrder($this->shop_id, $ids, $code);
            if ($model) {
                $model->count += 1;
                if ($model->count >= $model->count_limit) {
                    $model->status = 0;
                }
                $model->user_use = $this->user_id;
                $model->addOderId($this->id);
                $this->discount_code_id = $model->id;
                $this->discount_code_value = $model->getSaleMoney($products);
                $this->order_total = ($this->order_total > $this->discount_code_value) ? ($this->order_total - $this->discount_code_value) : 0;
                if ($this->save()) {
                    $model->save(false);
                }
            }
        }
    }

    function cancer()
    {
        $tru_product = false;
        if ($this->transport_id) {
            $claShipping = new \common\components\shipping\ClaShipping();
            $claShipping->loadVendor(['method' => $this->transport_type]);
            switch ($this->transport_type) {
                case \common\components\shipping\ClaShipping::METHOD_GHN:
                    $options['data'] = array(
                        'OrderCode' => $this->transport_id
                    );
                    break;
                default:
                    $options['data'] = array(
                        'OrderCode' => $this->transport_id
                    );
                    break;
            }
            $data = $claShipping->cancerOrder($options);
            if (isset($data['success']) && $data['success']) {
                $status_old = $this->status;
                if ($this->status > 1) {
                    $tru_product = true;
                }
                $this->status = 0;
                if ($this->save()) {
                    if (\common\models\gcacoin\CoinConfinement::cancerShopOrder($this)) {
                        if ($tru_product) {
                            $this->setPromotion(0);
                        }
                        $data = [
                            'order_id' => $this->id,
                            'time' => time(),
                            'status' => 0,
                            'type' => $this->transport_type,
                            'content' => Yii::t('app', 'update_to_shop'),
                            'created_at' => time()
                        ];
                        $kt = \common\models\order\OrderShopHistory::saveData($data);
                        $sve = \common\models\notifications\Notifications::updateStatusOrder($this);
                        return ['code' => 1];
                    } else {
                        $this->status = $status_old;
                        $this->save(false);
                        return ['code' => 0, 'error' => "Cập nhật lỗi. Vùi lòng thử lại sau ít phút."];
                    }
                }
            } else {
                return ['code' => 0, 'error' => "Không thể hủy đơn hàng. Vui lòng liên hệ với đơn vị giao hàng để biết thêm chi tiết."];
            }
        } else {
            if ($this->status == 1 || $this->status == 2 || $this->status == 3) {
                $status_old = $this->status;
                if ($this->status > 1) {
                    $tru_product = true;
                }
                $this->status = 0;
                if ($this->save()) {
                    if (\common\models\gcacoin\CoinConfinement::cancerShopOrder($this)) {
                        if ($tru_product) {
                            $this->setPromotion(0);
                        }
                        $data = [
                            'order_id' => $this->id,
                            'time' => time(),
                            'status' => 0,
                            'type' => $this->transport_type,
                            'content' => Yii::t('app', 'update_to_shop'),
                            'created_at' => time()
                        ];
                        $kt = \common\models\order\OrderShopHistory::saveData($data);
                        $sve = \common\models\notifications\Notifications::updateStatusOrder($this);
                        return ['code' => 1];
                    } else {
                        $this->status = $status_old;
                        return ['code' => 0, 'error' => "Cập nhật lỗi. Vùi lòng thử lại sau ít phút."];
                    }
                }
            }
        }
        return ['code' => 0, 'error' => "Cập nhật lỗi. Vùi lòng thử lại sau ít phút."];
    }
    function updateStatus($status, $options = [])
    {
        $user_id = isset($options['user_id']) ? $options['user_id'] : Yii::$app->user->id;
        if (in_array($status, [1, 2, 3, 4]) && $user_id == $this->shop_id && !$this->transport_id) {
            if ($this->status < $status) {
                $this->status = $status;
                if ($this->save()) {
                    if ($this->status == 2) {
                        $this->setPromotion();
                    }
                    $data = [
                        'order_id' => $this->id,
                        'time' => time(),
                        'status' => $this->status,
                        'type' => $this->transport_type,
                        'content' => Yii::t('app', 'update_to_shop'),
                        'created_at' => time()
                    ];
                    $kt = \common\models\order\OrderShopHistory::saveData($data);
                    $sve = \common\models\notifications\Notifications::updateStatusOrder($this);
                    return true;
                }
            }
        }
        return false;
    }

    public function getOneByUser($id, $user_id)
    {
        return self::find()->where(['id' => $id])->andWhere("user_id = '$user_id' OR shop_id = '$user_id'")->one();
    }
}

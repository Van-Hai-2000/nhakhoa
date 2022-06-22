<?php

namespace common\models\order;

use backend\modules\product\Product;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "order_item".
 *
 * @property string $id
 * @property string $shop_id
 * @property string $order_id
 * @property string $order_id
 * @property string $product_id
 * @property string $code
 * @property string $quantity
 * @property string $price
 * @property string $created_at
 * @property integer $status
 */
class OrderItem extends \common\models\ActiveRecordC
{

    const STATUS_OUT_STOCK = 2; // out of stock
    const STATUS_IN_STOCK = 3; // in stock
    const STATUS_SUCCESS = 6; // success
    public $_merrors = '';

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'quantity', 'price', 'shop_id'], 'required'],
            [['price'], 'number'],
            [['order_id', 'created_at'], 'integer'],
            [['quantity'], 'number'],
            [['product_id', 'code', 'status', 'length', 'width', 'height', 'weight'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Gian hàng',
            'order_id' => 'Đơn hàng',
            'product_id' => 'Sản phẩm',
            'quantity' => 'Số lượng',
            'price' => 'Giá',
            'created_at' => 'Ngày tạo',
            'code' => 'Mã sản phẩm',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function updateStatusByOrderId($order_id, $status_new, $status_old)
    {
        $sql = 'UPDATE order_item SET status=' . $status_new . ' WHERE order_id=' . $order_id . ' AND status=' . $status_old;
        Yii::$app->db->createCommand($sql)->execute();
    }

    public static function getByShopOrder($id)
    {
        $data = (new \yii\db\Query())->select('r.*, t.avatar_path, t.avatar_name, t.name as product_name, t.alias')
            ->from('order_item r')
            ->join('LEFT JOIN', 'product t', 't.id = r.product_id')
            ->where(['r.order_id' => $id])
            ->orderBy('r.id DESC')
            ->all();
        return $data;
    }

    public static function getByShopInOrder($shop_id, $order_id)
    {
        $data = (new \yii\db\Query())->select('r.*, t.avatar_path, t.avatar_name, t.name as product_name, t.alias')
            ->from('order_item r')
            ->join('LEFT JOIN', 'product t', 't.id = r.product_id')
            ->where(['r.shop_id' => $shop_id, 'r.order_id' => $order_id])
            ->orderBy('r.id DESC')
            ->all();
        return $data;
    }

    public static function getProductByShopId($shop_id)
    {
        $data = (new \yii\db\Query())->select('t.name, t.weight, r.price, r.quantity')
            ->from('order_item r')
            ->join('LEFT JOIN', 'product t', 't.id = r.product_id')
            ->where(['r.order_id' => $shop_id])
            ->orderBy('r.id DESC')
            ->all();
        return $data;
    }

    public static function getInfo4($order_id)
    {
        $info3 = [
            'weight' => 0,
            'height' => 0,
            'width' => 0,
            'length' => 0,
        ];
        $data = (new \yii\db\Query())->select('t.name, t.weight, t.width, t.height, t.length, r.quantity')
            ->from('order_item r')
            ->join('LEFT JOIN', 'product t', 't.id = r.product_id')
            ->where(['r.order_id' => $order_id])
            ->orderBy('r.id DESC')
            ->all();
        if ($data) {
            foreach ($data as $tg) {
                $info3['weight'] += $tg['weight'] * $tg['quantity'];
                $info3['width'] += $tg['width'] * $tg['quantity'];
                $info3['height'] += $tg['height'];
                $info3['length'] += $tg['length'];
            }
        }
        return $info3;
    }

    public static function getAllOrderItem($user_id, $options = [])
    {
        $condition = 't.user_before_product=:user_id';
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
        // $condition .=" AND c.affiliate_user_id = :user_id";
        $data = (new \yii\db\Query())
            ->select('t.*, r.status, r.updated_at, r.status_check_cancer, r.time_check_cancer')
            ->from('order_item t')
            ->rightJoin('order r', 'r.id = t.order_id')
            // ->leftJoin('affiliate_click c', 't.product_id = c.object_id')
            ->where($condition, $params)
            ->orderBy('id DESC')

            ->all();
        return $data;
    }

    public static function getAllOrderItemIsShop($user_id, $options = [])
    {
        $condition = 't.user_before_shop=:user_id';
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
            ->select('t.*, r.status, r.updated_at, r.status_check_cancer, r.time_check_cancer')
            ->from('order_item t')
            ->rightJoin('order r', 'r.id = t.order_id')
            ->where($condition, $params)
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

    public static function calculatorCommission($data)
    {
        $keyComplete = Order::ORDER_DELIVERING;
        $keyWaiting = Order::ORDER_WAITING_PROCESS;
        $keyDestroy = Order::ORDER_CANCEL;
        // init commission
        $commission = [
            $keyComplete => 0,
            $keyWaiting => 0,
            $keyDestroy => 0
        ];
        // init total price
        $totalPrice = [
            $keyComplete => 0,
            $keyWaiting => 0,
            $keyDestroy => 0
        ];
        //
        foreach ($data as $item) {
            if ($item['status'] == $keyComplete) {
                $totalPrice[$keyComplete] += $item['sale_before_product'];
            } else if ($item['status'] == $keyDestroy) {
                $totalPrice[$keyDestroy] += $item['sale_before_product'];
            } else {
                $totalPrice[$keyWaiting] += $item['sale_before_product'];
            }
        }
        //
        $commission[$keyComplete] = $totalPrice[$keyComplete];
        $commission[$keyWaiting] = $totalPrice[$keyWaiting];
        $commission[$keyDestroy] = $totalPrice[$keyDestroy];
        //
        return $commission;
    }

    public static function calculatorCommissionIsShop($data)
    {
        $keyComplete = Order::ORDER_DELIVERING;
        $keyWaiting = Order::ORDER_WAITING_PROCESS;
        $keyDestroy = Order::ORDER_CANCEL;
        // init commission
        $commission = [
            $keyComplete => 0,
            $keyWaiting => 0,
            $keyDestroy => 0
        ];
        // init total price
        $totalPrice = [
            $keyComplete => 0,
            $keyWaiting => 0,
            $keyDestroy => 0
        ];
        //
        foreach ($data as $item) {
            if ($item['status'] == $keyComplete) {
                $totalPrice[$keyComplete] += $item['sale_before_shop'];
            } else if ($item['status'] == $keyDestroy) {
                $totalPrice[$keyDestroy] += $item['sale_before_shop'];
            } else {
                $totalPrice[$keyWaiting] += $item['sale_before_shop'];
            }
        }
        //
        $commission[$keyComplete] = $totalPrice[$keyComplete];
        $commission[$keyWaiting] = $totalPrice[$keyWaiting];
        $commission[$keyDestroy] = $totalPrice[$keyDestroy];
        //
        return $commission;
    }

    public static function getOrderByAffiliateIds($ids, $user_before_product = null)
    {
        $user_before_product = $user_before_product ? $user_before_product : Yii::$app->user->id;
        $items = (new \yii\db\Query())->from('order_item')->select("product_id, count('product_id') as count")->where(['product_id' => $ids, 'user_before_product' => $user_before_product])->groupBy("product_id")->all();
        $data = [];
        if ($items) {
            foreach ($items as $item) {
                $data[$item['product_id']] = $item['count'];
            }
        }
        return $data;
    }

    function getAffiliate($user_id = null)
    {
        $product = \common\models\product\Product::findOne($this->product_id);
        if ($product) {
            $user_id = $user_id ? $user_id  : Yii::$app->user->id;
            if (!$product->canBuyFor($user_id)) {
                $this->_merrors = $product->_merrors;
                return false;
            }
            $change_v = \common\models\gcacoin\Gcacoin::getPerMoneyCoin();
            $price = $product->getPriceC1($this->quantity);
            $price =  $price > 0 ?  $price : 1000;
            $this->price =  $product->getPrice($this->quantity);
            $this->weight = $product->weight;
            $this->height = $product->height;
            $this->width = $product->width;
            $this->length = $product->length;
            $this->code = $product['code'];
            $this->name = $product['name'];
            $this->avatar_path = $product['avatar_path'];
            $this->avatar_name = $product['avatar_name'];
            if ($product->status_affiliate == \common\components\ClaLid::STATUS_ACTIVED) {
                $shop = \common\models\shop\Shop::findOne($product->shop_id);
                $this->sale_before_product = ($product->affiliate_gt_product * $price / 100) / $change_v * $this->quantity;
                $this->sale_before_shop = ($shop->affiliate_gt_shop * $price / 100) / $change_v * $this->quantity;
                $this->sale_buy_shop_coin = ($product->affiliate_m_v * $price / 100) / $change_v * $this->quantity;
                $this->sale_buy_shop_money = ($product->affiliate_m_ov * $price / 100) / $change_v * $this->quantity;
                $this->sale_charity = ($product->affiliate_charity * $price / 100) / $change_v * $this->quantity;
                $this->sale = ($product->affiliate_safe * $price / 100) / $change_v * $this->quantity;
                $this->sale_admin = ($shop->affiliate_admin * $price / 100) / $change_v * $this->quantity;
                $this->user_before_product = $product->userBeforeProduct();
                $this->user_before_shop = $product->userBeforeShop();
                $this->price = $this->price > 0 ? $this->price : ($price - $product->affiliate_safe * $price / 100);
                // echo "<pre>";
                // print_r($this->attributes);
                // die();
            }
            $this->price = $this->price > 0 ? $this->price : $price;
            return true;
        }
        $this->_merrors = 'Sản phẩm '.$this->product_id.' không tồn tại.';;
        return false;
    }

    static function getAllCharity($options = null)
    {
        $query = (new Query())->from('order_item ot')->select('ot.*, ot.name as product_name, o.*')->rightJoin('order o', "o.id = ot.order_id")->where("sale_charity > 0");
        if (isset($options['status']) !== false) {
            $query->andWhere(['o.status' => $options['status']]);
        }
        if (isset($options['sum']) && $options['sum']) {
            $query->select("SUM(ot.sale_charity) as sum");
            $tg = $query->all();
            return $tg[0]['sum'];
        }
        if (isset($options['count']) && $options['count']) {
            return $query->count();
        }
        if (isset($options['limit']) && $options['limit']) {
            $query->limit($options['limit']);
            $page = isset($options['page']) && $options['page'] >= 1 ? $options['page'] : 1;
            $offset = $options['limit'] * ($page - 1);
            $query->offset($offset);
        }
        // echo "<pre>";
        // print_r($query->all());
        // die();
        return $query->orderBy('ot.id DESC')->all();
    }
}

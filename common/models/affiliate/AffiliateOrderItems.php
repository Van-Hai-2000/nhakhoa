<?php

namespace common\models\affiliate;

use Yii;
use common\models\order\Order;

/**
 * This is the model class for table "affiliate_order_items".
 *
 * @property string $id
 * @property string $affiliate_user_id
 * @property string $affiliate_id
 * @property string $affiliate_click_id
 * @property string $affiliate_order_id
 * @property string $order_id
 * @property string $product_id
 * @property string $product_price
 * @property string $product_qty
 * @property string $created_at
 * @property string $track_commission_percent
 * @property string $commission thưởng
 */
class AffiliateOrderItems extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'affiliate_order_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['affiliate_user_id', 'affiliate_id', 'affiliate_click_id', 'affiliate_order_id', 'order_id', 'product_id', 'product_qty', 'created_at'], 'integer'],
                [['product_price', 'track_commission_percent', 'commission'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'affiliate_user_id' => 'Affiliate User ID',
            'affiliate_id' => 'Affiliate ID',
            'affiliate_click_id' => 'Affiliate Click ID',
            'affiliate_order_id' => 'Affiliate Order ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'product_price' => 'Product Price',
            'product_qty' => 'Product Qty',
            'created_at' => 'Created At',
            'track_commission_percent' => 'Track Commission Percent',
            'commission' => 'Commission',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            } 
            return true;
        } else {
            return false;
        }
    }

    public static function getAllOrderItemForOverview($options = []) {
        $condition = '1=1';
        $params = [];
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
            ->select('t.*, r.status')
            ->from('affiliate_order_items t')
            ->rightJoin('order r', 'r.id = t.order_id')
            ->where($condition, $params)
            ->orderBy('id ASC')
            ->all();
        return $data;
    }
    
    public static function getAllOrderItem($user_id, $options = []) {
        $condition = 't.affiliate_user_id=:user_id';
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
                ->select('t.*, r.status, c.created_at AS click_time')
                ->from('affiliate_order_items t')
                ->rightJoin('order r', 'r.id = t.order_id')
                ->leftJoin('affiliate_click c', 'c.id = t.affiliate_click_id')
                ->where($condition, $params)
                ->orderBy('id DESC')
                ->all();
        return $data;
    }

    public static function calculatorCommission($data) {
        $config = AffiliateConfig::findOne(\common\components\ClaLid::ROOT_SITE_ID);
        //
        $keyComplete = Order::ORDER_DELIVERY_SUCCESS;
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
                $totalPrice[$keyComplete] += $item['product_price'] * $item['product_qty'];
            } else if ($item['status'] == $keyWaiting) {
                $totalPrice[$keyWaiting] += $item['product_price'] * $item['product_qty'];
            } else if ($item['status'] == $keyDestroy) {
                $totalPrice[$keyDestroy] += $item['product_price'] * $item['product_qty'];
            }
        }
        //
        $commission[$keyComplete] = ($totalPrice[$keyComplete] * $config['commission_order']) / 100;
        $commission[$keyWaiting] = ($totalPrice[$keyWaiting] * $config['commission_order']) / 100;
        $commission[$keyDestroy] = ($totalPrice[$keyDestroy] * $config['commission_order']) / 100;
        //
        return $commission;
    }

    public static function getAffiliateOrderItemByOrderId($order_id) {
        $data = AffiliateOrderItems::find()->select('*')
            ->where('order_id=:order_id', [':order_id' => $order_id])
            ->asArray()
            ->all();
        $result = [];
        if(isset($data) && $data) {
            foreach($data as $item) {
                $result[$item['product_id']] = $item;
            }
        }
        return $result;
    }

    public static function getAffilidateOrderItemByAffiliateId($order_status, $affiliate_id) {
        $condition = 't.affiliate_id=:affiliate_id AND r.status=:status';
        $params = [
            ':affiliate_id' => $affiliate_id,
            ':status' => $order_status
        ];
        $data = (new \yii\db\Query())
            ->select('t.*, r.status')
            ->from('affiliate_order_items t')
            ->rightJoin('order r', 'r.id = t.order_id')
            ->where($condition, $params)
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

}

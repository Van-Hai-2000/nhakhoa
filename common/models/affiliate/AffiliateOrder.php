<?php

namespace common\models\affiliate;

use Yii;
use common\models\order\Order;

/**
 * This is the model class for table "affiliate_order".
 *
 * @property string $id
 * @property string $affiliate_user_id User người tạo affiliate
 * @property string $affiliate_id
 * @property string $affiliate_click_id
 * @property string $order_id
 * @property string $product_ids ID các sp rel order_products
 * @property string $created_at
 * @property int $type Loai order, mac dinh la order khi dat hang
 */
class AffiliateOrder extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'affiliate_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['affiliate_user_id', 'affiliate_id', 'affiliate_click_id', 'order_id', 'created_at', 'type'], 'integer'],
            [['product_ids'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'affiliate_user_id' => 'Affiliate User ID',
            'affiliate_id' => 'Affiliate ID',
            'affiliate_click_id' => 'Affiliate Click ID',
            'order_id' => 'Order ID',
            'product_ids' => 'Product Ids',
            'created_at' => 'Created At',
            'type' => 'Type',
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

    public static function countOrder($order_status, $user_id, $options = [])
    {
        $condition = 't.affiliate_user_id=:user_id AND r.status=:order_status';
        $params = [
            ':user_id' => $user_id,
            ':order_status' => $order_status
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
        if (isset($options['affiliate_id']) && $options['affiliate_id']) {
            $condition .= ' AND t.affiliate_id=:affiliate_id';
            $params[':affiliate_id'] = $options['affiliate_id'];
        }
        $count = (new \yii\db\Query())->select('COUNT(*)')
            ->from('affiliate_order AS t')
            ->rightJoin('order AS r', 'r.id=t.order_id')
            ->where($condition, $params)
            ->scalar();
        return $count;
    }

    public static function getOrder($order_status, $user_id)
    {
        $data = (new \yii\db\Query())
            ->select('*')
            ->from('affiliate_order as t')
            ->rightJoin('order AS r', 'r.id = t.order_id')
            ->where('r.affiliate_user_id=:user_id AND r.status=:status', [
                ':user_id' => $user_id,
                ':order_status' => $order_status
            ])->all();
        return $data;
    }

    public static function getAllOrder($user_id, $options = [])
    {
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
            ->select('t.*, c.operating_system, r.campaign_source, r.aff_type, r.campaign_name')
            ->from('affiliate_order t')
            ->leftJoin('affiliate_click c', 'c.id = t.affiliate_click_id')
            ->leftJoin('affiliate_link r', 'r.id = t.affiliate_id')
            ->where($condition, $params)
            ->all();
        return $data;
    }

    public static function getOrderStatusName($order_status)
    {
        $return = '';
        if ($order_status == Order::ORDER_DELIVERY_SUCCESS) {
            $return = 'Thành công';
        } else if ($order_status == Order::ORDER_CANCEL) {
            $return = 'Hủy';
        } else if ($order_status == Order::ORDER_WAITING_PROCESS) {
            $return = 'Chờ duyệt';
        }
        return $return;
    }

    public static function getOrderByAffiliateIds($idsArray)
    {
        $result = [];
        if (isset($idsArray) && $idsArray) {
            $data = AffiliateOrder::find()->select('*')
                ->where('affiliate_id IN (' . join(',', $idsArray) . ')')
                ->asArray()
                ->all();
            if (isset($data) && $data) {
                foreach ($data as $item) {
                    $result[$item['affiliate_id']][] = $item;
                }
            }
        }
        return $result;
    }

}

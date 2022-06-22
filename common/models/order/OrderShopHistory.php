<?php

namespace common\models\order;

use Yii;
use common\components\shipping\ClaShipping;

/**
 * This is the model class for table "{{%order_shop_history}}".
 *
 * @property string $id
 * @property integer $order_id
 * @property integer $time
 * @property integer $status
 * @property string $content
 * @property integer $created_at
 */
class OrderShopHistory extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_shop_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'time', 'status', 'created_at'], 'required'],
            [['order_id', 'time', 'status', 'created_at'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'time' => 'Time',
            'status' => 'Status',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }

    public static function getStatus($id, $method)
    {
        if ($method) {
            $claShipping = new ClaShipping();
            $claShipping->loadVendor(['method' => $method]);
            return $claShipping->vendor->getStatus($id);
        }
        $data = [
            '1' => Yii::t('app', 'ReadyToPick'),
            '2' => Yii::t('app', 'Picking_default'),
            '3' => Yii::t('app', 'Delivering_default'),
            '4' => Yii::t('app', 'Finish'),
            '0' => Yii::t('app', 'Cancel'),
            '7' => Yii::t('app', 'Returned'),
        ];
        return isset($data[$id]) ? $data[$id] : '';
    }

    public static function getSystemStatus($status, $method)
    {
        if ($method) {
            $claShipping = new ClaShipping();
            $claShipping->loadVendor(['method' => $method]);
            return $claShipping->vendor->getSystemStatus($status);
        }
        $data = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '0' => 0,
            '7' => 0,
        ];
        return isset($data[$status]) ? $data[$status] : '';
    }

    public static function getStatusId($id, $method)
    {
        if ($method) {
            $claShipping = new ClaShipping();
            $claShipping->loadVendor(['method' => $method]);
            return $claShipping->vendor->getStatusId($id);
        }
        return $id;
    }

    public static function saveData($data)
    {
        $model = new OrderShopHistory();
        $model->order_id = $data['order_id'];
        $model->time = $data['time'];
        $model->status = $data['status'];
        $model->type = $data['type'];
        $model->content = $data['content'];
        $model->created_at = time();
        return $model->save();
    }

    public static function getHistory($order_id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data = self::find()->where(['order_id' => $order_id])->orderBy('time')->asArray()->all();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['status'] = self::getStatus($data[$i]['status'], $data[$i]['type']);
        }
        return $data;
    }

    public static function getLast($order_id)
    {
        return self::find()->where(['order_id' => $order_id])->orderBy('time DESC')->one();
    }
}

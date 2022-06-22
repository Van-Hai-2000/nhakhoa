<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "order_log".
 *
 * @property string $id
 * @property string $order_id
 * @property string $user_id
 * @property string $content
 * @property string $created_at
 */
class OrderLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'order_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['order_id', 'user_id', 'created_at'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'order_id' => 'Đơn hàng',
            'user_id' => 'Người thực hiện',
            'content' => 'Sau thay đổi',
            'created_at' => 'Thời gian thực hiện',
        ];
    }
    
    /**
     * get all log by order id
     * @param type $id
     */
    public static function getLogsByOrderId($id) {
        $data = (new \yii\db\Query())->select('*')
                ->from('order_log')
                ->where('order_id=:order_id', [':order_id' => $id])
                ->orderBy('id ASC')
                ->all();
        return $data;
    }

}

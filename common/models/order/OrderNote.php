<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "order_note".
 *
 * @property string $id
 * @property string $order_id
 * @property string $user_id
 * @property string $note
 * @property string $created_at
 */
class OrderNote extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'order_note';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['order_id', 'user_id'], 'integer'],
            [['note'], 'required'],
            [['note'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'order_id' => 'Đơn hàng',
            'user_id' => 'Người ghi chú',
            'note' => 'Ghi chú',
            'created_at' => 'Thời gian tạo',
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

}

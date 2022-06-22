<?php

namespace common\models\order;

use \Yii\behaviors\TimestampBehavior;
use \Yii\db\ActiveRecord;

class OrderImages extends ActiveRecord
{

    public static function tableName()
    {
        return 'order_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['order_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'path'], 'string'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
//            'user_id' => 'ID',
//            'value' => 'Số xu cần chuyển',
//            'bank_id' => 'Tài khoản ngân hàng',
        ];
    }


}
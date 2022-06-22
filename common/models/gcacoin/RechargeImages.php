<?php

namespace common\models\gcacoin;

use \Yii\behaviors\TimestampBehavior;
use \Yii\db\ActiveRecord;

class RechargeImages extends ActiveRecord
{

    public static function tableName()
    {
        return 'recharge_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recharge_id'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['recharge_id', 'height', 'width','created_at', 'updated_at'], 'integer'],
            [['name', 'path', 'display_name','alias'], 'string'],
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
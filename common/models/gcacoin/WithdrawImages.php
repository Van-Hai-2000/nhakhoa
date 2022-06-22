<?php

namespace common\models\gcacoin;

use \Yii\behaviors\TimestampBehavior;
use \Yii\db\ActiveRecord;

class WithdrawImages extends ActiveRecord
{

    public static function tableName()
    {
        return 'withdraw_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['withdraw_id'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['withdraw_id', 'height', 'width','created_at', 'updated_at'], 'integer'],
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
            'id' => 'ID',
            'user_id' => 'ID',
            'value' => 'Số V cần chuyển',
            'bank_id' => 'Tài khoản ngân hàng',
        ];
    }


}
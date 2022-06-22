<?php

namespace common\models\gcacoin;

use \Yii\behaviors\TimestampBehavior;
use \Yii\db\ActiveRecord;

class Recharge extends ActiveRecord
{
    public $avatar_1;
    public $avatar_2;
    
    public static function tableName()
    {
        return 'recharge';
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','admin_id', 'value'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['user_id', 'admin_id', 'value','created_at', 'updated_at'], 'integer'],
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
            'id' => 'ID',
            'user_id' => 'ID',
            'value' => 'Số V cần nạp',
            'avatar_1' => 'Ảnh giao dịch chuyển khoản trên mobile banking, nạp tiền tại ngân hàng.(File ảnh .jpg .jpeg .png)',
            'avatar_2' => 'Ảnh tin nhắn thông báo chuyển khoản thành công từ ngân hàng.(File ảnh .jpg .jpeg .png)',
        ];
    }


}
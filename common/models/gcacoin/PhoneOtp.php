<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/20/2018
 * Time: 5:05 PM
 */

namespace common\models\gcacoin;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PhoneOtp extends ActiveRecord
{
    public static function tableName()
    {
        return 'phone_otp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['created_at', 'updated_at'], 'integer'],
            [['phone'], 'string'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Số điện thoại',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static  function getModel()
    {
        $model = self::find()->one();
        if (!$model) {
            $model = new self();
            $model->phone = 0;
            $model->created_at = $model->updated_at = time();
        }
        return $model;
    }
}

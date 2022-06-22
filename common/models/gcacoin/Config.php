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

class Config extends ActiveRecord
{
    public static function tableName()
    {
        return 'gcacoin_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'gcacoin'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['money', 'gcacoin', 'created_at', 'updated_at', 'transfer_fee_type'], 'number'],
            [['transfer_fee','hour_confinement', 'sale'], 'number'],
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
            'money' => 'Số tiền',
            'transfer_fee' => 'Giá trị phí chuyển',
            'transfer_fee_type' => 'Loại phí chuyển',
            'hour_confinement' => 'Thời gian tạm giữ V(Đơn vị: Giây)',
            'sale' => 'Khuyến mãi toàn trang(%)',
            'gcacoin' => 'Số điểm tương ứng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getConfig()
    {
        $model = self::find()->one();
        return ($model) ? $model : new self();
    }

    public static function getHourConfinement()
    {
        $model = self::find()->one();
        return ($model) ? $model->hour_confinement : 0;
    }

    function getMaxFreeTransfer($coin)
    {
        if ($this->transfer_fee_type == 1) {
            return $coin / ($this->transfer_fee / 100 + 1);
        }
        return $coin - $this->transfer_fee;
    }

    function getUnitTransfer()
    {
        if ($this->transfer_fee_type == 1) {
            return '%';
        }
        return 'V';
    }

    function getCoinTransferFee($coin)
    {
        if ($this->transfer_fee_type == 1) {
            return $coin + ($coin * $this->transfer_fee / 100);
        }
        return $coin +  $this->transfer_fee;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            Yii::$app->cache->delete(\common\components\ClaLid::KEY_CONFIG_COIN);
            return true;
        } else {
            return false;
        }
    }
}

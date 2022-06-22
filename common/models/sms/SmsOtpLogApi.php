<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_otp_log_api".
 *
 * @property string $id
 * @property integer $code
 * @property string $message
 * @property string $tran_id
 * @property string $oper
 * @property integer $total_sms
 * @property integer $created_at
 */
class SmsOtpLogApi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_otp_log_api';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'total_sms', 'created_at'], 'integer'],
            [['message', 'tran_id', 'oper'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'message' => 'Message',
            'tran_id' => 'Tran ID',
            'oper' => 'Oper',
            'total_sms' => 'Total Sms',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
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
}

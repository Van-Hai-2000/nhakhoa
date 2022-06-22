<?php
/**
 * Created by PhpStorm.
 * User: hungtm
 * Date: 11/17/2018
 * Time: 10:25 AM
 */

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_otp_log".
 *
 * @property string $id
 * @property string $phone
 * @property string $otp_number
 * @property string $operator
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SmsOtpLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_otp_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['phone'], 'string', 'max' => 15],
            [['otp_number'], 'string', 'max' => 10],
            [['operator'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'otp_number' => 'Mã OTP',
            'operator' => 'Cú pháp',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $phone
     * @param $otp
     * @param $operator
     * @param $status
     * @return bool
     */
    public static function logOtp($phone, $otp, $operator, $status)
    {
        $log = new SmsOtpLog();
        $log->phone = $phone;
        $log->otp_number = $otp;
        $log->operator = $operator;
        $log->status = $status;
        return $log->save();
    }
}

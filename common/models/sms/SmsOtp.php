<?php
/**
 * Created by PhpStorm.
 * User: hungtm
 * Date: 11/17/2018
 * Time: 10:25 AM
 */

namespace common\models\sms;

use common\components\ClaLid;
use Yii;

/**
 * This is the model class for table "sms_otp".
 *
 * @property string $id
 * @property string $phone
 * @property string $otp_number
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SmsOtp extends \yii\db\ActiveRecord
{

    const EXPIRE_MINUTES = 30; // Thời hạn sử dụng OTP trong bao nhiêu phút Phút
    const MAX_SMS_ONE_DAY = 100; // Số tin nhắn tối đa 1 ngày

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_otp';
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
            'otp_number' => 'Otp Number',
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
     * Generate numeric OTP
     * @param $n
     * @return string
     */
    public static function generateNumericOTP($n)
    {
        $generator = '1357902468';
        //
        $result = '';
        //
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        //
        return $result;
    }

    /**
     * Get Phone model
     * @param $phone
     * @return array|SmsOtp|null|\yii\db\ActiveRecord
     */
    public static function getPhoneModel($phone)
    {
        $model = SmsOtp::find()
            ->where('phone=:phone', [
                ':phone' => $phone
            ])
            ->one();
        //
        if ($model === NULL) {
            $model = new SmsOtp();
            $model->phone = $phone;
            return $model;
        }
        //
        return $model;
    }

    /**
     * Check OTP correct
     * @param $phone
     * @param $otp
     * @return bool
     */
    public static function checkOtpCorrect($phone, $otp)
    {
        $timeCheck = time() - (self::EXPIRE_MINUTES * 60);
        $model = SmsOtp::find()
            ->where('status=:status AND phone=:phone AND otp_number=:otp_number AND updated_at >= :time_check', [
                ':status' => ClaLid::STATUS_DEACTIVED,
                ':phone' => $phone,
                ':otp_number' => $otp,
                ':time_check' => $timeCheck
            ])
            ->one();
        if ($model === NULL) {
            return false;
        }
        return true;
    }

    /**
     * @param $phone
     * @param $otp
     * @return bool
     */
    public static function updateStatusOtpToUsed($phone, $otp)
    {
        $model = SmsOtp::find()
            ->where('status=:status AND phone=:phone AND otp_number=:otp_number', [
                ':status' => ClaLid::STATUS_DEACTIVED,
                ':phone' => $phone,
                ':otp_number' => $otp,
            ])
            ->one();
        if($model === NULL) {
            return false;
        }
        $model->status = ClaLid::STATUS_ACTIVED;
        if ($model->save()) {
            $log = SmsOtpLog::find()
                ->where('status=:status AND phone=:phone AND otp_number=:otp_number', [
                    ':status' => ClaLid::STATUS_DEACTIVED,
                    ':phone' => $phone,
                    ':otp_number' => $otp,
                ])
                ->one();
            $log->status = ClaLid::STATUS_ACTIVED;
            $log->save();
        }
        return true;
    }

    /**
     * @param $phone
     * @return int|string
     */
    public static function countGetOtpToday($phone)
    {
        $startToday = mktime(0, 0, 1, date('m'), date('d'), date('Y'));
        $count = SmsOtpLog::find()
            ->where('phone=:phone AND created_at >= :start_today', [
                ':phone' => $phone,
                ':start_today' => $startToday
            ])
            ->count();
        return $count;
    }

}

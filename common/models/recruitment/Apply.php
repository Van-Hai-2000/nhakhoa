<?php

namespace common\models\recruitment;

use Yii;

/**
 * This is the model class for table "apply".
 *
 * @property string $id
 * @property string $recruitment_id
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $location_desire
 * @property string $user_id
 * @property string $name
 * @property integer $sex
 * @property string $birthday
 * @property string $birthplace
 * @property string $identity_card
 * @property integer $married_status
 * @property string $address
 * @property string $hotline
 * @property string $email
 * @property string $province_id
 * @property string $district_id
 * @property string $income_desire
 * @property integer $height
 * @property integer $weight
 * @property string $reason
 * @property string $certificate
 * @property string $created_at
 * @property string $updated_at
 */
class Apply extends \yii\db\ActiveRecord {

    public $day;
    public $month;
    public $year;
    //
    public $avatar;
    public $src_avatar;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'apply';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['recruitment_id', 'location_desire', 'name', 'birthday', 'birthplace', 'identity_card', 'address', 'hotline', 'email', 'province_id', 'income_desire', 'reason'], 'required'],
            [['recruitment_id', 'location_desire', 'user_id', 'sex', 'birthplace', 'married_status', 'province_id', 'district_id', 'income_desire', 'height', 'weight', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['avatar_path', 'avatar_name', 'name', 'address', 'email'], 'string', 'max' => 255],
            [['identity_card', 'hotline'], 'string', 'max' => 20],
            [['reason'], 'string', 'max' => 1000],
            [['certificate'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'recruitment_id' => 'Recruitment ID',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'location_desire' => 'Nguyện vọng làm việc tại',
            'user_id' => 'User ID',
            'name' => 'Họ tên ứng viên',
            'sex' => 'Giới tính',
            'birthday' => 'Ngày sinh',
            'birthplace' => 'Nơi sinh',
            'identity_card' => 'CMND',
            'married_status' => 'Tình trạng hôn nhân',
            'address' => 'Địa chỉ',
            'hotline' => 'Điện thoại di động',
            'email' => 'Email',
            'province_id' => 'Tình thành',
            'district_id' => 'Quận huyện',
            'income_desire' => 'Thu nhập mong muốn',
            'height' => 'Chiều cao (cm)',
            'weight' => 'Cân nặng',
            'reason' => 'Vì sao bạn muốn ứng tuyển vị trí này?',
            'certificate' => 'Chứng chỉ khác',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDays() {
        $days = [
            '' => 'Ngày',
        ];
        for ($i = 1; $i <= 31; $i++) {
            $key = $i < 10 ? '0' . $i : $i;
            $days[$key] = $key;
        }
        return $days;
    }

    public function getMonths() {
        $months = [
            '' => 'Tháng'
        ];
        for ($i = 1; $i <= 12; $i++) {
            $key = $i < 10 ? '0' . $i : $i;
            $months[$key] = $key;
        }
        return $months;
    }

    public function getYears() {
        $years = [
            '' => 'Năm'
        ];
        for ($i = 2000; $i >= 1950; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }
    
    public function beforeSave($insert) {
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

}

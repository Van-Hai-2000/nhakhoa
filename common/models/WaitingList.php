<?php

namespace common\models;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "waiting_list".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $branch_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $doctor_id
 * @property integer $description
 * @property integer $medical_record_id
 * @property integer $status_delete
 * @property integer $stt
 */
class WaitingList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'waiting_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'branch_id'], 'required'],
            [['user_id', 'branch_id', 'status', 'created_at', 'updated_at', 'doctor_id','medical_record_id','status_delete','stt'], 'integer'],
            [['description'], 'string', 'max' => 500],
            [['status_delete'], 'default', 'value' => 0],
            [['stt'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Số thứ tự',
            'user_id' => 'Bệnh nhân',
            'stt' => 'STT',
            'branch_id' => 'Chi nhánh',
            'status' => 'Trạng thái',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian khám',
            'doctor_id' => 'Bác sỹ khám',
            'description' => 'Nội dung khám',
            'medical_record_id' => 'Mã hồ sơ bệnh án',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            }else{
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    static function getStatus()
    {
        return [
            0 => 'Chờ khám',
            1 => 'Đang khám',
            2 => 'Hoàn thành'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\user\User::className(), ['id' => 'user_id'])->select('id,username,phone');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'doctor_id'])->select('id,username,fullname');
    }

    public static function getCount($provider, $fieldValue)
    {
        $total = 0;

        foreach ($provider as $item) {
            if ($item['status'] == $fieldValue) {
                $total += 1;
            }
        }
        return $total;
    }

    static function getColor($status)
    {
        switch ($status) {
            case 1:
                $color = 'green';
                break;
            case 2:
                $color = 'blue';
                break;
            default:
                $color = 'orange';
                break;
        }
        return $color;
    }

    static function getTime($status,$time)
    {
        switch ($status) {
            case 1:
                $time = date('d-m-Y H:i:s',$time);
                break;
            case 2:
                $time = date('d-m-Y H:i:s',$time);
                break;
            default:
                $time = 'Đang chờ khám';
                break;
        }
        return $time;
    }
}

<?php

namespace common\models\medical_record;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\LoaiMau;
use common\models\log\Activerecordlog;
use frontend\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "factory".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $medical_record_id
 * @property integer $factory_id
 * @property double $money
 * @property integer $device_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $time_return
 * @property string $insurance_code
 * @property string $insurance_code_private
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status_delete
 * @property integer $user_action_id
 */
class Factory extends ClaActiveRecordLog
{
    const STATUS_WAITING = 1;
    const STATUS_LAY_MAU = 2;
    const STATUS_DANG_GIAO = 3;
    const STATUS_SUCCESS = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'medical_record_id', 'factory_id', 'device_id', 'branch_id', 'admin_id', 'created_at', 'updated_at','quantity','status','status_delete','user_action_id'], 'integer'],
            [['medical_record_id', 'factory_id', 'device_id'], 'required'],
            [['money'], 'number'],
            [['time_return'], 'safe'],
            [['insurance_code', 'insurance_code_private','phone'], 'string', 'max' => 255],
            [['status_delete'], 'default', 'value' => 0],
            [['user_id', 'medical_record_id', 'factory_id', 'device_id', 'branch_id', 'admin_id', 'created_at', 'updated_at','quantity','status','status_delete'], 'filter', 'filter' => 'intval'],
            [['money'], 'filter', 'filter' => 'doubleval'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'B???nh nh??n',
            'medical_record_id' => 'M?? h??? s?? b???nh ??n',
            'factory_id' => 'X?????ng',
            'quantity' => 'S??? l?????ng',
            'money' => 'S??? ti???n',
            'device_id' => 'Lo???i m???u',
            'branch_id' => 'Chi nh??nh',
            'admin_id' => 'Ng?????i ?????t',
            'time_return' => 'Ng??y tr??? m???u',
            'insurance_code' => 'M?? b???o h??nh',
            'insurance_code_private' => 'M?? b???o h??nh n???i b???',
            'created_at' => 'Ng??y g???i m???u',
            'phone' => 'S??? ??i???n tho???i ng?????i ?????t',
            'user_action_id' => 'Ng?????i g???i y??u c???u',
            'updated_at' => 'Updated At',
            'status' => 'Tr???ng th??i',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if($this->created_at){
                    $this->updated_at =  $this->created_at;
                }else{
                    $this->created_at = $this->updated_at = time();
                }
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    static function getStatus(){
        return [
            self::STATUS_WAITING => 'Ch??? x??c nh???n',
            self::STATUS_LAY_MAU => '??ang l???y m???u',
            self::STATUS_DANG_GIAO => '??ang giao',
            self::STATUS_SUCCESS => 'Ho??n th??nh',
        ];
    }

    public function getUser()

    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username,phone');
    }

    public function getBranch()

    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    public function getUserAdmin()

    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'factory_id'])->select('id,username,fullname');
    }

    public function getLoaimau()

    {
        return $this->hasOne(LoaiMau::className(), ['id' => 'device_id'])->select('id,name');
    }

}

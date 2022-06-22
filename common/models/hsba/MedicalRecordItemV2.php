<?php

namespace common\models\hsba;

use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "medical_record_item".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $medical_record_id
 * @property integer $branch_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class MedicalRecordItemV2 extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_item_v2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'medical_record_id', 'branch_id'], 'required'],
            [['user_id', 'medical_record_id', 'branch_id', 'is_new', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Bệnh nhân',
            'medical_record_id' => 'Mã hồ sơ',
            'branch_id' => 'Chi nhánh',
            'description' => 'Lời dặn của bác sỹ',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public function getUser()

    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username');
    }
    public function getMedicalRecord()

    {
        return $this->hasOne(MedicalRecordV2::className(), ['id' => 'medical_record_id'])->select('id,name');
    }

    public function getBranch()

    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

}

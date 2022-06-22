<?php

namespace common\models\medical_record;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use Yii;

/**
 * This is the model class for table "medical_record_log".
 *
 * @property string $id
 * @property integer $medical_record_id
 * @property string $user_id
 * @property string $record_before
 * @property string $record_after
 * @property string $action
 * @property integer $type
 * @property integer $type_id
 * @property integer $created_at
 * @property integer $model
 * @property integer $branch_id
 */
class MedicalRecordLog extends \yii\db\ActiveRecord
{
    const TYPE_1 = 1; //appointment
    const TYPE_2 = 2; //PaymentHistory
    const TYPE_3 = 3; //Factory
    const TYPE_4 = 4; //ThuChi
    const TYPE_5 = 5; //MedicalRecordItemMedicine
    const TYPE_6 = 6; //MedicalRecordItemCommission
    const TYPE_7 = 7; //MedicalRecord
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id', 'type', 'type_id', 'created_at','branch_id'], 'integer'],
            [['record_before', 'record_after'], 'string'],
            [['user_id', 'action'], 'string', 'max' => 45],
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'medical_record_id' => 'Mã HSBA',
            'user_id' => 'Người thực hiện',
            'record_before' => 'Nội dung trước thay đổi',
            'record_after' => 'Nội dung sau thay đổi',
            'action' => 'Thao tác',
            'type' => 'Type',
            'model' => 'Nơi thay đổi',
            'type_id' => 'Type ID',
            'created_at' => 'Thời gian',
            'branch_id' => 'Chi nhánh',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
            return true;
        } else {
            return false;
        }
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'user_id'])->select('id,fullname');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }
}

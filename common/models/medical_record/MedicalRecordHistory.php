<?php

namespace common\models\medical_record;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use common\models\product\Product;
use Yii;

/**
 * This is the model class for table "medical_record_history".
 *
 * @property string $id
 * @property integer $medical_record_id
 * @property integer $product_id
 * @property string $note
 * @property integer $doctor_id
 * @property integer $admin_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status_delete
 * @property integer $admin_name
 * @property integer $branch_id
 */
class MedicalRecordHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id', 'product_id', 'doctor_id', 'admin_id','branch_id'], 'required'],
            [['medical_record_id', 'product_id', 'doctor_id', 'admin_id', 'created_at', 'updated_at','status_delete','branch_id'], 'integer'],
            [['note'], 'string', 'max' => 500],
            [['admin_name'], 'string', 'max' => 255],
            [['status_delete'], 'default', 'value' => 0],
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
            'product_id' => 'Thủ thuật',
            'note' => 'Ghi chú',
            'doctor_id' => 'Bác sĩ',
            'admin_id' => 'Người tạo',
            'admin_name' => 'Người tạo',
            'created_at' => 'Thời gian',
            'updated_at' => 'Updated At',
            'branch_id' => 'Chi nhánh',
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

    public function getDoctor(){
        return $this->hasOne(UserAdmin::className(),['id' => 'doctor_id'])->select('fullname,id');
    }

    public function getBranch(){
        return $this->hasOne(Branch::className(),['id' => 'branch_id'])->select('name,id');
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['id' => 'product_id'])->select('name,id');
    }
}

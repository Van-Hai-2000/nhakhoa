<?php

namespace common\models\user;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use common\models\product\Product;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "medical_record_item_child".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $medical_record_id
 * @property integer $medical_record_item_id
 * @property integer $product_id
 * @property string $product_name
 * @property string $chuan_doan
 * @property string $description
 * @property integer $doctor_id
 * @property integer $status
 * @property double $money
 * @property integer $quantity
 * @property integer $branh_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $note
 * @property integer $type_sale
 * @property integer $sale_value
 */
class MedicalRecordItemChild extends \yii\db\ActiveRecord
{
    const TYPE_THUTHUAT = 1;
    const TYPE_THUOC= 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'medical_record_id', 'medical_record_item_id', 'product_id', 'doctor_id'], 'required'],
            [['user_id', 'medical_record_id', 'medical_record_item_id', 'product_id', 'doctor_id', 'status', 'quantity', 'branh_id', 'created_at', 'updated_at','type_sale'], 'integer'],
            [['chuan_doan', 'description'], 'string'],
            [['money','sale_value'], 'number'],
            [['product_name'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'medical_record_id' => 'Medical Record ID',
            'medical_record_item_id' => 'Medical Record Item ID',
            'product_id' => 'Thủ thuật',
            'product_name' => 'Product Name',
            'chuan_doan' => 'Chuan Doan',
            'description' => 'Description',
            'doctor_id' => 'Bác sỹ thực hiện',
            'status' => 'Trạng thái',
            'money' => 'Số tiền',
            'quantity' => 'Số lần',
            'branh_id' => 'Chi nhánh',
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

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'doctor_id'])->select('id,username,fullname');
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username');
    }

    public function getMedicalRecord()
    {
        return $this->hasOne(MedicalRecord::className(), ['id' => 'medical_record_id'])->select('id,name');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branh_id'])->select('id,name');
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}

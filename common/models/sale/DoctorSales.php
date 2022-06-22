<?php

namespace common\models\sale;

use backend\models\UserAdmin;
use common\models\product\Product;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "doctor_sales".
 *
 * @property integer $id
 * @property integer $doctor_id
 * @property string $doctor_name
 * @property double $money
 * @property integer $product_id
 * @property integer $medical_record_id
 * @property integer $week
 * @property integer $month
 * @property integer $year
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $item_child_id
 * @property integer $status_delete
 * @property integer $payment_id
 * @property integer $branch_id
 */
class DoctorSales extends \yii\db\ActiveRecord
{
    public $type_time; //Lọc theo ngày/ tháng
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctor_sales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id', 'money'], 'required'],
            [['is_new', 'doctor_id', 'product_id', 'medical_record_id', 'week', 'month', 'year', 'created_at', 'updated_at','item_child_id','status_delete','branch_id','payment_id'], 'integer'],
            [['money'], 'number'],
            [['doctor_name'], 'string', 'max' => 255],
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
            'doctor_id' => 'Bác sĩ',
            'doctor_name' => 'Doctor Name',
            'money' => 'Doanh số',
            'product_id' => 'Thủ thuật thực hiện',
            'medical_record_id' => 'Medical Record ID',
            'week' => 'Week',
            'month' => 'Month',
            'year' => 'Year',
            'created_at' => 'Thời gian',
            'updated_at' => 'Updated At',
            'type_time' => 'Lọc theo',
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

    static function getValueSale($value,$money_payment){
        $money = ($value/8) * $money_payment;
        return $money;
    }

    public function getUserAdmin()

    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'doctor_id'])->select('id,username,fullname');
    }

    public function getProduct()

    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->select('id,name');
    }

}

<?php

namespace common\models\sale;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use common\models\product\Product;
use common\models\product\ProductCategory;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "operation_sales".
 *
 * @property integer $id
 * @property integer $product_id
 * @property double $money
 * @property integer $branch_id
 * @property integer $doctor_id
 * @property integer $medical_record_id
 * @property integer $day
 * @property integer $week
 * @property integer $month
 * @property integer $year
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $item_child_id
 * @property integer $status_delete
 * @property integer $product_category_id
 */
class OperationSales extends \yii\db\ActiveRecord
{
    public $type_time; //Lọc theo ngày/ tháng
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operation_sales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'money'], 'required'],
            [['product_id', 'branch_id', 'doctor_id', 'medical_record_id', 'day', 'week', 'month', 'year', 'created_at', 'updated_at','type_time','item_child_id','status_delete','product_category_id'], 'integer'],
            [['money'], 'number'],
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
            'product_id' => 'Thủ thuật',
            'money' => 'Doanh số',
            'branch_id' => 'Chi nhánh',
            'doctor_id' => 'Bác sĩ thực hiện',
            'product_category_id' => 'Nhóm thủ thuật',
            'medical_record_id' => 'Medical Record ID',
            'day' => 'Day',
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

    public function getUserAdmin()

    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'doctor_id'])->select('id,username,fullname');
    }

    public function getProduct()

    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->select('id,name');
    }

    public function getProductCategory()

    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id'])->select('id,name');
    }

    public function getBranch()

    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    static function show($key,$item){
        if($key == 'product_id'){
            return isset($item->product->name) && $item->product->name ? $item->product->name : '';
        }
        if($key == 'doctor_id'){
            return isset($item->userAdmin->fullname) && $item->userAdmin->fullname ? $item->userAdmin->fullname : '';
        }
        if($key == 'branch_id'){
            return isset($item->branch->name) && $item->branch->name ? $item->branch->name : '';
        }
        return $item->$key;
    }
}

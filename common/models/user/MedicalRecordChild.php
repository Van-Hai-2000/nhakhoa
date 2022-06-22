<?php

namespace common\models\user;

use common\components\ClaActiveRecordLog;
use common\models\product\Product;
use common\models\product\ProductCategory;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "medical_record_child".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $medical_record_id
 * @property integer $product_category_id
 * @property integer $product_id
 * @property integer $quantity
 * @property integer $quantity_use
 * @property double $money
 * @property integer $created_at
 * @property integer $updated_at
 */
class MedicalRecordChild extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'medical_record_id'], 'required'],
            [['user_id', 'medical_record_id', 'product_category_id', 'product_id', 'quantity', 'created_at', 'updated_at','quantity_use'], 'integer'],
            [['money'], 'number'],
            [['quantity_use'], 'default','value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Tên bệnh nhân',
            'medical_record_id' => 'Mã bệnh án',
            'product_category_id' => 'Nhóm thủ thuật',
            'product_id' => 'Thủ thuật',
            'quantity' => 'Số lần dự kiến',
            'quantity_use' => 'Số lần đã thực hiện',
            'money' => 'Đơn giá',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian cập nhật',
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

    public function getProduct()

    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->select('id,name,description,price,price_market');
    }

    public function getUser()

    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username');
    }
    public function getProductCategory()

    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id'])->select('id,name');
    }
}

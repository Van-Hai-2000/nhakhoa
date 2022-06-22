<?php

namespace common\models\appointment;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\product\ProductCategory;
use Yii;

/**
 * This is the model class for table "appointment".
 *
 * @property integer $id
 * @property integer $doctor_id
 * @property integer $time
 * @property string $description
 * @property string $name
 * @property string $phone
 * @property integer $medical_record_id
 * @property integer $product_category_id
 * @property string $product_id
 * @property integer $branch_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status_delete
 */
class Appointment extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'appointment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id', 'medical_record_id', 'product_category_id', 'branch_id', 'created_at', 'updated_at', 'status', 'user_id', 'status_delete'], 'integer'],
            [['time', 'phone', 'branch_id', 'name'], 'required'],
            [['description'], 'string'],
            [['name', 'product_id'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 500],
            [['phone'], 'string', 'max' => 50],
            [['status_delete'], 'default', 'value' => 0],
            [['doctor_id', 'medical_record_id', 'product_category_id', 'branch_id', 'created_at', 'updated_at', 'status', 'user_id', 'status_delete'], 'filter', 'filter' => 'intval'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctor_id' => 'Bác sĩ thực hiện',
            'time' => 'Thời gian',
            'description' => 'Ghi chú',
            'name' => 'Họ và tên',
            'phone' => 'Số điện thoại',
            'medical_record_id' => 'Mã hồ sơ bệnh án',
            'product_category_id' => 'Nhóm thủ thuật',
            'product_id' => 'Product ID',
            'branch_id' => 'Chi nhánh',
            'address' => 'Địa chỉ',
            'status' => 'Đã đến',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Updated At',
            'user_id' => 'Bệnh nhân',
        ];
    }

    public function beforeSave($insert)
    {
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

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'doctor_id'])->select('id,username,fullname');
    }

    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id'])->select('id,name');
    }
}

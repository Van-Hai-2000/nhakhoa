<?php

namespace common\models\user;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\medicine\Medicine;
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
 * @property integer $medicine_id
 * @property integer $status_delete
 */
class MedicalRecordItemMedicine extends ClaActiveRecordLog
{
    const TYPE_THUTHUAT = 1;
    const TYPE_THUOC= 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_item_medicine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'medical_record_id', 'medical_record_item_id', 'medicine_id', 'doctor_id'], 'required'],
            [['user_id', 'medical_record_id', 'medical_record_item_id', 'medicine_id', 'doctor_id', 'status', 'quantity', 'branh_id', 'created_at', 'updated_at','status_delete'], 'integer'],
            [['chuan_doan', 'description'], 'string'],
            [['money'], 'number'],
            [['product_name'], 'string', 'max' => 255],
            [['status_delete'], 'default', 'value' => 0],
            [['user_id', 'medical_record_id', 'medical_record_item_id', 'medicine_id', 'doctor_id', 'status', 'quantity', 'branh_id', 'created_at', 'updated_at','status_delete'], 'filter', 'filter' => 'intval'],
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
            'user_id' => 'Bệnh nhân',
            'medical_record_id' => 'Mã HSBA',
            'medical_record_item_id' => 'ID ngày khám',
            'medicine_id' => 'Tên thuốc - thiết bị',
            'product_name' => 'Product Name',
            'chuan_doan' => 'Chuan Doan',
            'description' => 'Description',
            'doctor_id' => 'Bác sỹ',
            'status' => 'Trạng thái',
            'money' => 'Đơn giá',
            'quantity' => 'Số lượng',
            'branh_id' => 'Chi nhánh',
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

    public function getMedicine()
    {
        return $this->hasOne(Medicine::className(), ['id' => 'medicine_id'])->select('id,name,price');
    }
    static public function getAllbyDoctor($options = [],$id = 0){
        $query = self::find()->where(['doctor_id' => $id]);

        if (isset($options['created_at']) && $options['created_at'] ) {
            $query->andWhere(['medical_record_item_medicine.created_at' => strtotime($options['created_at'])]);
        }

        $data = $query->joinWith(['medicine', 'userAdmin','user'])->orderBy('created_at DESC')->all();
        return $data;
    }
}

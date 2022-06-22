<?php

namespace common\models\medical_record;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use Yii;

/**
 * This is the model class for table "medical_record_item_commission".
 *
 * @property string $id
 * @property integer $medical_record_id
 * @property integer $medical_record_item_id
 * @property integer $user_id
 * @property double $value
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property integer $product_id
 * @property integer $price
 * @property integer $price_payment
 * @property integer $payment_status
 * @property integer $medical_record_item_child_id
 */
class MedicalRecordItemCommission extends ClaActiveRecordLog
{
    const TYPE_1 = 1; //hưởng theo %
    const TYPE_2 = 2; //hưởng thẳng tiền mặt

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_item_commission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id', 'medical_record_item_id', 'user_id', 'value'], 'required'],
            [['medical_record_id', 'medical_record_item_id', 'status', 'created_at', 'updated_at','payment_status','product_id','medical_record_item_child_id'], 'integer'],
            [['user_id','value'], 'string','max' => 255],
            [['type'], 'string','max' => 50],
            [['price','price_payment'], 'number'],
            [['medical_record_id', 'medical_record_item_id', 'status', 'created_at', 'updated_at','payment_status','product_id','medical_record_item_child_id'], 'filter', 'filter' => 'intval'],
            [['price','price_payment'], 'filter', 'filter' => 'doubleval'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'medical_record_id' => 'Mã hồ sơ bệnh án',
            'medical_record_item_id' => 'Ngày khám',
            'user_id' => 'Người hưởng hoa hồng',
            'product_id' => 'ID thủ thuật',
            'price_payment' => 'Số tiền tính hoa hồng',
            'Price' => 'Đơn giá thủ thuật',
            'value' => 'Giá trị',
            'type' => 'Loại hưởng',
            'status' => 'Trạng thái',
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

}

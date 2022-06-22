<?php

namespace common\models\voucher;

use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use Yii;

/**
 * This is the model class for table "medical_record_voucher".
 *
 * @property string $id
 * @property integer $medical_record_id
 * @property integer $voucher_id
 * @property string $product_ids
 * @property integer $type
 * @property double $type_value
 * @property double $money_start
 * @property double $money_end
 * @property integer $created_at
 * @property integer $updated_at
 */
class MedicalRecordVoucher extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_voucher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id', 'voucher_id'], 'required'],
            [['medical_record_id', 'voucher_id', 'type', 'created_at', 'updated_at','user_id','branch_id'], 'integer'],
            [['type_value', 'money_start', 'money_end','total_money'], 'number'],
            [['product_ids'], 'string', 'max' => 255],
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
            'user_id' => 'Bệnh nhân',
            'voucher_id' => 'Voucher ID',
            'product_ids' => 'Product Ids',
            'type' => 'Type',
            'type_value' => 'Type Value',
            'money_start' => 'Money Start',
            'money_end' => 'Money End',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'branch_id' => 'Chi nhánh',
            'total_money' => 'Tổng tiền được giảm',
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

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['id' => 'voucher_id'])->select('id,title,voucher');
    }

}

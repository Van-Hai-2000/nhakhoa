<?php

namespace common\models\hsba;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\components\ClaNhakhoa;
use common\models\branch\Branch;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment_history".
 *
 * @property integer $id
 * @property integer $medical_record_id
 * @property double $money
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type_payment
 * @property integer $note
 * @property integer $medical_record_item_child_id
 */
class PaymentHistoryV2 extends ClaActiveRecordLog
{
    const TYPE_SALE_1 = 1; //Giảm thẳng vào tiền mặt
    const TYPE_SALE_2 = 2; //Giảm theo %
    const TYPE_PAYMENT_1 = 1; //Thanh toán tiền mặt
    const TYPE_PAYMENT_2 = 2; //Thanh toán chuyển khoản
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_history_v2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id', 'money', 'branch_id'], 'required'],
            [['medical_record_id', 'branch_id', 'admin_id', 'created_at', 'updated_at','type_payment','medical_record_item_child_id'], 'integer'],
            [['money'], 'number'],
            [['note'], 'safe'],
            [['medical_record_id', 'branch_id', 'admin_id', 'created_at', 'updated_at','type_payment'], 'filter', 'filter' => 'intval'],
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
            'medical_record_id' => 'Mã hồ sơ bệnh án',
            'money' => 'Số tiền',
            'branch_id' => 'Chi nhánh',
            'admin_id' => 'Người thanh toán',
            'created_at' => 'Thời gian',
            'updated_at' => 'Updated At',
            'type_payment' => 'Hình thức thanh toán',
            'note' => 'Ghi chú',
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

    public function getUserAdmin()

    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'admin_id'])->select('id,username,fullname');
    }

    //Tính số tiền thực thu
    static function getMoney($total_money,$type_sale,$sale_value){
        $money = $total_money - $sale_value;
        if ($type_sale) {
            if ($type_sale == ClaNhakhoa::TYPE_MONEY_1) {
                $money = ($total_money * (100 - $sale_value)) / 100;
            }
        }
        if($type_sale == null){
            $money = ($total_money * (100 - $sale_value)) / 100;
        }
        return $money;
    }

    static function getTypePayment(){
        return [
            self::TYPE_PAYMENT_1 => 'Tiền mặt',
            self::TYPE_PAYMENT_2 => 'Chuyển khoản',
        ];
    }
}

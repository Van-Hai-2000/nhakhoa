<?php

namespace common\models\commission_v2;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\hsba\MedicalRecordItemCommissionV2;
use common\models\medical_record\MedicalRecordItemCommission;
use common\models\user\MedicalRecordItemMedicine;
use frontend\models\User;
use Yii;

/**
 * This is the model class for table "commission".
 *
 * @property integer $id
 * @property integer $medical_record_id
 * @property integer $user_id
 * @property integer $type
 * @property integer $type_id
 * @property integer $type_commission
 * @property integer $value_commission
 * @property integer $money
 * @property integer $branch_id
 * @property integer $payment_id
 * @property integer $status_delete
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommissionV2 extends ClaActiveRecordLog
{
    const COMMISSION_MEDICINE_VALUE = 5;
    const TYPE_PAYMENT = 1;
    const TYPE_MEDICINE = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'commission_v2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id','user_id'], 'required'],
            [['medical_record_id', 'user_id', 'type', 'type_id', 'type_commission', 'branch_id','payment_id','status_delete'], 'integer'],
            [['money','value_commission'], 'number'],
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
            'user_id' => 'Người hưởng',
            'type' => 'Loại hoa hồng',
            'type_commission' => 'Hưởng theo',
            'value_commission' => 'Giá trị',
            'money' => 'Tổng tiền tính hoa hồng',
            'branch_id' => 'Thanh toán tại',
            'payment_id' => 'Mã thanh toán',
            'created_at' => 'Thời gian',
            'updated_at' => 'Thời gian cập nhật'
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
        return $this->hasOne(UserAdmin::className(), ['id' => 'user_id'])->select('id,username,fullname');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    static function show($key,$item){
        if($key == 'user_id'){
            return isset($item->userAdmin->fullname) && $item->userAdmin->fullname ? $item->userAdmin->fullname : '';
        }
        if($key == 'branch_id'){
            return isset($item->branch->name) && $item->branch->name ? $item->branch->name : '';
        }
        return $item->$key;
    }

    static function getType(){
        return [
            self::TYPE_PAYMENT => 'Thanh toán',
            self::TYPE_MEDICINE => 'Thuốc',
        ];
    }

    public static function getMoneyWaiting($model)
    {
        $type = '';
        if($model['type'] == 1 && $model['type_money'] == 1){
            $item_commission = MedicalRecordItemCommission::findOne($model['item_commission_id']);
            if($item_commission){
                $user_ids = explode(',', $item_commission->user_id);
                $value_commission = explode(',', $item_commission->value);
                if(in_array($model['admin_id'],$user_ids)){
                    foreach ($user_ids as $key => $user_id){
                        if($user_id == $model['admin_id'] && $item_commission->price_payment < $item_commission->price){
                            $money = $item_commission->price * $value_commission[$key] / 100;
                            return [
                                'waiting' => true,
                                'value' => number_format($model['money']).'/ '.number_format($money)
                            ];
                        }
                    }
                }
            }
        }
        return [
            'waiting' => false,
            'value' => number_format($model['money'])
        ];
    }
}

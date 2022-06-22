<?php

namespace common\models\commission;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\medical_record\MedicalRecordItemCommission;
use common\models\user\MedicalRecordItemMedicine;
use frontend\models\User;
use Yii;

/**
 * This is the model class for table "commission".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property double $value
 * @property double $money
 * @property double $total_money
 * @property integer $user_id
 * @property integer $medical_record_id
 * @property integer $branch_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type_money
 * @property integer $total_money_received
 * @property integer $item_commission_id
 * @property integer $item_medicine_id
 * @property integer $type
 * @property integer $status_delete
 */
class Commission extends ClaActiveRecordLog
{
    const COMMISSION_MEDICINE_VALUE = 5;
    const TYPE_PAYMENT = 1;
    const TYPE_MEDICINE = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'commission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id'], 'required'],
            [['admin_id', 'user_id', 'medical_record_id', 'branch_id', 'created_at', 'updated_at','type','type_money','item_commission_id','item_medicine_id','status_delete'], 'integer'],
            [['value', 'money', 'total_money','total_money_received'], 'number'],
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
            'admin_id' => 'Người hưởng thụ',
            'value' => 'Giá trị',
            'money' => 'Tiền thực hưởng',
            'total_money' => 'Tổng tiền hóa đơn',
            'user_id' => 'Bệnh nhân',
            'medical_record_id' => 'Mã hồ sơ bệnh án',
            'branch_id' => 'Thanh toán tại',
            'created_at' => 'Thời gian',
            'type' => 'Loại hoa hồng',
            'updated_at' => 'Thời gian cập nhật',
            'type_money' => 'Hưởng theo',
            'total_money_received' => 'Tổng tiền thanh toán để tính hoa hồng',
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username,phone');
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'admin_id'])->select('id,username,fullname');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    public function getItemCommission()
    {
        return $this->hasOne(MedicalRecordItemCommission::className(), ['id' => 'item_commission_id'])->select('id,product_id');
    }

    public function getItemMedicine()
    {
        return $this->hasOne(MedicalRecordItemMedicine::className(), ['id' => 'item_medicine_id'])->select('id,medicine_id');
    }

    static function show($key,$item){
        if($key == 'user_id'){
            return isset($item->user->username) && $item->user->username ? $item->user->username : '';
        }
        if($key == 'admin_id'){
            return isset($item->userAdmin->fullname) && $item->userAdmin->fullname ? $item->userAdmin->fullname : '';
        }
        if($key == 'branch_id'){
            return isset($item->branch->name) && $item->branch->name ? $item->branch->name : '';
        }
        return $item->$key;
    }

    static function getTypeMoney(){
        return [
            MedicalRecordItemCommission::TYPE_1 => 'Theo %',
            MedicalRecordItemCommission::TYPE_2 => 'Tiền trực tiếp',
        ];
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

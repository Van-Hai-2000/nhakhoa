<?php

namespace common\models\thuchi;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\hsba\PaymentHistoryV2;
use common\models\user\PaymentHistory;
use common\models\user\User;
use Yii;

/**
 * This is the model class for table "thu_chi".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $category_id
 * @property double $money
 * @property integer $time
 * @property string $note
 * @property integer $admin_id
 * @property integer $nguoi_chi
 * @property integer $branch_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $payment_id
 * @property integer $medical_record_id
 * @property integer $type_payment
 * @property integer $status_delete
 * @property integer $object_type
 * @property integer $object_id
 * @property integer $user_id
 */
class ThuChiV2 extends ClaActiveRecordLog
{
    const TYPE_THU = 1;
    const TYPE_CHI = 2;
    const TYPE_THU_PAYMENT = 1;
    const TYPE_THU_MORE = 2;
    const TYPE_CHI_NCC = 3; // Khoản chi đặt xưởng
    const TYPE_CHI_MORE = 4; // Khoản chi khác

    const OBJECT_TYPE_XUONG = 1; //Khoản thu, chi phát sinh do đặt xưởng

    const TYPE_PAYMENT_1 = 1; // Thanh toán bằng tiền mặt
    const TYPE_PAYMENT_2 = 2; // Thanh toán qua chuyển khoản
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'thu_chi_v2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'money', 'time'], 'required'],
            [['type', 'category_id', 'admin_id', 'nguoi_chi', 'branch_id', 'created_at', 'updated_at','user_id','type_id','ncc_id','payment_id','type_payment','medical_record_id','status_delete','object_type','object_id'], 'integer'],
            [['money'], 'number'],
            [['note'], 'string'],
            [['time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['type_payment'], 'default', 'value' => PaymentHistory::TYPE_PAYMENT_1],
            [['status_delete'], 'default', 'value' => 0],
            [['type', 'category_id', 'admin_id', 'nguoi_chi', 'branch_id', 'created_at', 'updated_at','user_id','type_id','ncc_id','payment_id','type_payment','medical_record_id','status_delete','object_type','object_id'], 'filter', 'filter' => 'intval'],
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
            'name' => 'Tên khoản thu, chi',
            'type' => 'Loại',
            'category_id' => 'Danh mục',
            'money' => 'Số tiền',
            'time' => 'Thời gian',
            'note' => 'Ghi chú',
            'admin_id' => 'Người tạo',
            'nguoi_chi' => 'Người thu, chi',
            'branch_id' => 'Chi nhánh',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Updated At',
            'user_id' => 'Bệnh nhân',
            'type_id' => 'Nguồn thu, chi',
            'ncc_id' => 'Nhà cung cấp',
            'medical_record_id' => 'Mã hồ sơ bệnh án',
            'type_payment' => 'Hình thức thanh toán',
            'payment_id' => 'Mã thanh toán',
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
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username,address');
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'nguoi_chi'])->select('id,username,fullname');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    public function getCategory()
    {
        return $this->hasOne(ThuChiCategory::className(), ['id' => 'category_id'])->select('id,name');
    }

    public function getPayment()
    {
        return $this->hasOne(PaymentHistoryV2::className(), ['id' => 'payment_id']);
    }

    static function show($key,$item){
        if($key == 'user_id'){
            return isset($item->user->username) && $item->user->username ? $item->user->username : '';
        }
        if($key == 'nguoi_chi'){
            return isset($item->userAdmin->username) && $item->userAdmin->username ? $item->userAdmin->username : '';
        }
        if($key == 'branch_id'){
            return isset($item->branch->name) && $item->branch->name ? $item->branch->name : '';
        }
        return $item->$key;
    }

    static function getType(){
        return [
            self::TYPE_CHI => 'Chi',
            self::TYPE_THU => 'Thu'
        ];
    }

    static function getTypeId(){
        return [
            self::TYPE_THU_PAYMENT => 'Thu từ thanh toán',
            self::TYPE_THU_MORE => 'Khoản thu khác',
            self::TYPE_CHI_NCC => 'Chi đặt xưởng',
            self::TYPE_CHI_MORE => 'Khoản chi khác'
        ];
    }

    static function getTypePayment(){
        return [
            PaymentHistory::TYPE_PAYMENT_1 => 'Tiền mặt',
            PaymentHistory::TYPE_PAYMENT_2 => 'Chuyển khoản',
        ];
    }
}

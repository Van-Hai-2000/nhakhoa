<?php

namespace common\models\user;

use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\product\Product;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "medical_record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $phone
 * @property double $total_money
 * @property double $money
 * @property integer $status
 * @property string $name
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $introduce
 * @property integer $introduce_id
 * @property integer $branch_id
 * @property integer $branch_related
 */
class MedicalRecord extends ClaActiveRecordLog
{
    const STATUS_WAITING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_SUCCESS_ALL = 4;
    const STATUS_DELETE = 3;
    public $qty;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'phone'], 'required'],
            [['user_id', 'status', 'updated_at', 'introduce_id', 'introduce','branch_id'], 'integer'],
            [['total_money', 'money','sale_money'], 'number'],
            [['note', 'ly_do'], 'string'],
            [['username', 'name','branch_related'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Mã HSBA',
            'user_id' => 'Bệnh nhân',
            'username' => 'Họ và tên',
            'phone' => 'Số điện thoại',
            'total_money' => 'Tổng thanh toán',
            'money' => 'Tổng tiền đã thanh toán',
            'status' => 'Trạng thái',
            'name' => 'Ghi chú',
            'note' => 'Đánh giá đợt điều trị của khách hàng',
            'ly_do' => 'Lý do khám',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian cập nhật',
            'introduce' => 'Nguồn',
            'introduce_id' => 'Người giới thiệu',
            'sale_money' => 'Tổng giảm giá',
            'branch_id' => 'Chi nhánh tạo',
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

    static function getStatus()
    {
        return [
            self::STATUS_WAITING => 'Đang chờ',
            self::STATUS_PROCESSING => 'Đang thực hiện',
            self::STATUS_SUCCESS => 'Đã T.toán 1p',
            self::STATUS_SUCCESS_ALL => 'Hoàn thành',
            self::STATUS_DELETE => 'Đã xóa',
        ];
    }

    static function getColor($status)
    {
        switch ($status) {
            case self::STATUS_WAITING:
                return 'orange';
                break;
            case self::STATUS_PROCESSING:
                return 'green';
                break;
            case self::STATUS_SUCCESS_ALL:
                return 'blue';
                break;
            case self::STATUS_SUCCESS:
                return 'cyan';
                break;
            case self::STATUS_DELETE:
                return 'red';
                break;
        }
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getBranch(){
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    static function getMedicalRecord($options = []){
        $query = self::find()->where(['<>','status',self::STATUS_DELETE]);
        if(isset($options['user_id']) && $options['user_id']){
            $query->andFilterWhere(['user_id' => $options['user_id']]);
        }
        $data = $query->asArray()->all();
        return array_column($data,'id','id');
    }
}

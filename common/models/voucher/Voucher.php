<?php

namespace common\models\voucher;

use common\components\ClaActiveRecordLog;
use common\models\branch\Branch;
use common\models\product\Product;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "voucher".
 *
 * @property string $id
 * @property string $title
 * @property string $voucher
 * @property integer $type
 * @property double $type_value
 * @property integer $start_time
 * @property integer $end_time
 * @property double $money_start
 * @property double $money_end
 * @property string $product_ids
 * @property integer $status
 * @property string $description
 * @property integer $branch_id
 * @property integer $day_start
 * @property integer $day_end
 * @property integer $created_at
 * @property integer $updated_at
 */
class Voucher extends ClaActiveRecordLog
{
    const TYPE_1 = 1; //Giảm giá theo %
    const TYPE_2 = 2; //Giảm giá trừ thẳng tiền

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher', 'type', 'type_value'], 'required'],
            [['type', 'status', 'branch_id', 'created_at', 'updated_at'], 'integer'],
            [['type_value', 'money_start', 'money_end'], 'number'],
            [['title', 'voucher'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['start_time', 'end_time', 'product_ids', 'day_start', 'day_end'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voucher' => 'Mã giảm giá',
            'type' => 'Loại giảm giá',
            'type_value' => 'Giá trị',
            'start_time' => 'Khung giờ bắt đầu',
            'end_time' => 'Khung giờ kết thúc',
            'money_start' => 'Số tiền nhỏ nhất hưởng voucher',
            'money_end' => 'Số tiền lớn nhất hưởng voucher',
            'status' => 'Trạng thái',
            'description' => 'Mô tả',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'title' => 'Tiêu đề',
            'product_ids' => 'Danh sách thủ thuật',
            'day_start' => 'Ngày bắt đầu',
            'day_end' => 'Ngày kết thúc',
            'branch_id' => 'Chi nhánh áp dụng',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    static function getStatus()
    {
        return [
            0 => 'Ẩn',
            1 => 'Hiển thị'
        ];
    }

    static function getType()
    {
        return [
            self::TYPE_1 => 'Giảm theo % thanh toán',
            self::TYPE_2 => 'Giảm thẳng vào giá'
        ];
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    static function getProduct()
    {
        $product = Product::find()->where(['status' => 1])->asArray()->all();
        return array_column($product, 'name', 'id');
    }

    static function checkValidate($voucher,$money, $time,$user_id,$branch_id){
        if(!$voucher){
            return 'Mã giảm giá không tồn tại';
        }

        if(isset($voucher->branch_id) && $voucher->branch_id && $voucher->branch_id != $branch_id){
            return 'Mã không áp dụng cho chi nhánh hiện tại';
        }

        $medical_record_voucher = MedicalRecordVoucher::find()->where(['user_id' => $user_id,'voucher_id' => $voucher->id])->one();
        if($medical_record_voucher){
            return 'Mã này đã được sử dụng';
        }

        if(isset($voucher->money_start) && $voucher->money_start && $money < $voucher->money_start){
            return 'Tổng tiền áp dụng mã giảm giá không có hiệu lực';
        }

        if(isset($voucher->money_end) && $voucher->money_end && $money > $voucher->money_end){
            return 'Tổng tiền áp dụng mã giảm giá không có hiệu lực';
        }

        if(isset($voucher->day_start) && $voucher->day_start){
            if($voucher->day_start > $time){
                return 'Thời gian áp dụng mã giảm giá không có hiệu lực';
            }
        }

        if(isset($voucher->day_end) && $voucher->day_end){
            if($voucher->day_end < $time){
                return 'Thời gian áp dụng mã giảm giá không có hiệu lực';
            }
        }
        return true;
    }

    static function getMoney($money,$type,$type_value){
        if($type == self::TYPE_1){
            $total_money = $money * $type_value / 100;
        }else{
            $total_money = $type_value;
        }
        return $total_money;
    }
}

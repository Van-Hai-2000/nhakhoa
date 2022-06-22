<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "discount_shop_code".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $name
 * @property integer $time_start
 * @property integer $time_end
 * @property integer $type_discount
 * @property string $value
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $user_id
 * @property string $user_updated
 */
class DiscountShopCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount_shop_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'quantity', 'time_start', 'time_end', 'name', 'type_discount', 'value', 'all'], 'required'],
            [['shop_id', 'type_discount', 'created_at', 'updated_at', 'status', 'user_id', 'quantity', 'all', 'count_limit'], 'integer'],
            [['value'], 'number'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['user_updated'], 'string', 'max' => 50],
            [['time_start', 'time_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Áp dụng cho doanh nghiệp',
            'name' => 'Tên đợt giảm giá',
            'time_start' => 'Thời gian bắt đầu',
            'time_end' => 'Thời gian hết hạn',
            'type_discount' => 'Loại giảm giá',
            'value' => 'Giá trị:(đ hoặc % theo loại)',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
            'quantity' => 'Số lượng mã tạo',
            'user_id' => 'User ID',
            'count' => 'Số lần đã sử dụng',
            'count_limit' => 'Số lần sử dụng/mã',
            'user_updated' => 'User Updated',
            'all' => 'Áp dụng cho',
        ];
    }

    public function beforeSave($insert)
    {
        $this->count_limit = $this->count_limit > 1 ? $this->count_limit : 1;
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
                $this->user_id = \common\components\ClaAll::getUserID();
            } else {
                $this->updated_at = time();
                $this->user_updated = \common\components\ClaAll::getUserID();
            }
            return true;
        } else {
            return false;
        }
    }
    function genCode()
    {
        $string = $this->shop_id . 'S' . $this->id . 'D' . rand(1000, 9999);
        $add = [
            'P',
            'G',
            'A',
        ];
        foreach ($add as $value) {
            $pos = rand(0, strlen($string)-1);
            $string = substr($string, 0, $pos) . $value . substr($string, $pos);
        }
        return $string;
    }

    function getListSave($arr = [], $dem = 0)
    {
        if ($dem > 4) {
            echo "<pre>";
            print_r($arr);
            die();
        }
        $count = count($arr);
        if ($count >= $this->quantity) {
            return $arr;
        } else {
            for ($i = $count; $i < $this->quantity; $i++) {
                $code = $this->genCode(time());
                $arr[$code] = [
                    'id' => $this->genCode(time()),
                    'shop_id' => $this->shop_id,
                    'time_start' => $this->time_start,
                    'time_end' => $this->time_end,
                    'type_discount' => $this->type_discount,
                    'value' => $this->value,
                    'count' => 0,
                    'count_limit' => $this->count_limit,
                    'user_use' => '',
                    'created_at' => $this->created_at,
                    'status' => 1,
                    'updated_at' => $this->updated_at,
                    'discount_shop_code_id' => $this->id,
                    'all' => $this->all,
                    'products' => $this->products,
                ];
            }
            return $this->getListSave($arr, $dem + 1);
        }
    }

    function afterSave($insert, $changedAttributes)
    {
        if ($this->status && !$this->user_updated) {
            $bulkInsertArray = $this->getListSave();
            if (count($bulkInsertArray) > 0) {
                $columnNameArray = ['id', 'shop_id', 'time_start', 'time_end', 'type_discount', 'value', 'count', 'count_limit', 'user_use', 'created_at', 'status', 'updated_at', 'discount_shop_code_id', 'all', 'products'];
                Yii::$app->db->createCommand()
                    ->batchInsert(
                        'discount_code',
                        $columnNameArray,
                        $bulkInsertArray
                    )->execute();
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}

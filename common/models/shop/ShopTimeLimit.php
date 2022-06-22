<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "{{%shop_time_limit}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $time
 * @property integer $coin
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $admin_id
 * @property integer $status
 */
class ShopTimeLimit extends \yii\db\ActiveRecord
{

    const ID_NOT_LIMMIT = 1;
    public $day;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_time_limit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'coin', 'created_at', 'updated_at', 'admin_id', 'status'], 'integer'],
            [['day'], 'number'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên gói',
            'time' => 'Thời gian',
            'day' => 'Số ngày',
            'coin' => 'Phí tính bằng V',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'admin_id' => 'Admin ID',
            'status' => 'Trạng thái',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            $this->updated_at = time();
            $this->time = $this->day * 24 * 60 * 60;
            return true;
        } else {
            return false;
        }
    }
}

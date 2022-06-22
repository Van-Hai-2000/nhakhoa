<?php

namespace common\models\affiliate;

use Yii;

/**
 * This is the model class for table "{{%affiliate}}".
 *
 * @property string $id
 * @property string $sale_for_app_status
 * @property string $sale_for_app_value
 * @property string $sale_befor_app_status
 * @property string $sale_befor_app_value
 * @property string $sale
 * @property integer $updated_at
 * @property integer $user_update
 */
class Affiliate extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_for_app_status', 'sale_for_app_value', 'sale_befor_app_status', 'sale_befor_app_value'], 'number'],
            [['sale_for_app_status', 'sale_for_app_value', 'sale_befor_app_status', 'sale_befor_app_value'], 'required'],
            [['updated_at', 'user_update'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_for_app_status' => 'Trạng thái affiliate tải app',
            'sale_for_app_value' => 'Giá trị affiliate tải app',
            'sale_befor_app_status' => 'Trạng thái affiliate tải app giới thiệu tải app',
            'sale_befor_app_value' => 'Giá trị affiliate tải app giới thiệu tải app',
            'updated_at' => 'Updated At',
            'user_update' => 'User Update',
        ];
    }
}

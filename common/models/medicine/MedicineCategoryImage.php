<?php

namespace common\models\medicine;

use common\components\ClaActiveRecordLog;
use Yii;

/**
 * This is the model class for table "product_category_image".
 *
 * @property string $id
 * @property string $medicine_category_id
 * @property string $path
 * @property string $name
 * @property string $display_name
 * @property string $height
 * @property string $width
 * @property string $order
 * @property string $created_at
 * @property string $color
 * @property integer $is_avatar
 */
class MedicineCategoryImage extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medicine_category_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medicine_category_id', 'path', 'name', 'display_name', 'height', 'width', 'created_at'], 'required'],
            [['medicine_category_id', 'height', 'width', 'created_at', 'is_avatar'], 'integer'],
            [['path', 'name', 'display_name'], 'string', 'max' => 255],
            [['color','order'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'medicine_category_id' => 'Id danh mục',
            'path' => 'Path',
            'name' => 'Name',
            'display_name' => 'Display Name',
            'height' => 'Height',
            'width' => 'Width',
            'order' => 'Order',
            'created_at' => 'Created At',
            'color' => 'Color',
            'is_avatar' => 'Is Avatar',
        ];
    }
}

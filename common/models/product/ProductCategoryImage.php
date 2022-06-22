<?php

namespace common\models\product;

use common\components\ClaActiveRecordLog;
use Yii;

/**
 * This is the model class for table "product_category_image".
 *
 * @property string $id
 * @property string $product_category_id
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
class ProductCategoryImage extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_category_id', 'path', 'name', 'display_name', 'height', 'width', 'created_at'], 'required'],
            [['product_category_id', 'height', 'width', 'created_at', 'is_avatar'], 'integer'],
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
            'product_category_id' => 'ID nhóm thủ thuật',
            'path' => 'Đường dẫn ảnh',
            'name' => 'Tên ảnh',
            'display_name' => 'Tên hiển thị',
            'height' => 'Chiều cao',
            'width' => 'Chiều rộng',
            'order' => 'Sắp xếp',
            'created_at' => 'Thời gian tạo',
            'color' => 'Màu',
            'is_avatar' => 'Is Avatar',
        ];
    }
}

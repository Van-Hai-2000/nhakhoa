<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_waiting_import_stock".
 *
 * @property string $id
 * @property string $product_id
 * @property integer $brand
 * @property string $price
 * @property string $code
 * @property string $color
 * @property string $size
 * @property string $quantity
 * @property string $created_at
 * @property string $updated_at
 * @property integer $order_id
 * @property integer $order_item_id
 * @property integer $weight
 */
class ProductWaitingImportStock extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_waiting_import_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'code', 'color', 'size'], 'required'],
            [['product_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 20],
            [['size'], 'string', 'max' => 10],
            [['brand', 'price', 'order_id', 'order_item_id', 'weight'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Sản phẩm',
            'code' => 'Mã gốc',
            'color' => 'Màu',
            'size' => 'Size',
            'quantity' => 'Số lượng nhập',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày sửa',
            'brand' => 'Thương hiệu',
            'price' => 'Giá',
            'weight' => 'Khối lượng',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

}

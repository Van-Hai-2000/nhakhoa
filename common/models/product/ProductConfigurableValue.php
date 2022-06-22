<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_configurable_value".
 *
 * @property string $id
 * @property string $product_id
 * @property string $code
 * @property string $barcode
 * @property string $attribute1_value
 * @property string $attribute2_value
 * @property string $attribute3_value
 * @property string $price
 * @property string $price_market
 */
class ProductConfigurableValue extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_configurable_value';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'attribute1_value', 'attribute2_value', 'attribute3_value'], 'integer'],
            [['code', 'barcode'], 'required'],
            [['price', 'price_market'], 'number'],
            [['code', 'barcode'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'code' => 'Code',
            'barcode' => 'Barcode',
            'attribute1_value' => 'Attribute1 Value',
            'attribute2_value' => 'Attribute2 Value',
            'attribute3_value' => 'Attribute3 Value',
            'price' => 'Price',
            'price_market' => 'Price Market',
        ];
    }

}

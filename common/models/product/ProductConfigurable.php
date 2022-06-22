<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_configurable".
 *
 * @property string $product_id
 * @property string $attribute1_id
 * @property string $attribute2_id
 * @property string $attribute3_id
 */
class ProductConfigurable extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_configurable';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id'], 'required'],
            [['product_id', 'attribute1_id', 'attribute2_id', 'attribute3_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'attribute1_id' => 'Attribute1 ID',
            'attribute2_id' => 'Attribute2 ID',
            'attribute3_id' => 'Attribute3 ID',
        ];
    }

}

<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_relation".
 *
 * @property string $product_id
 * @property string $relation_id
 * @property string $created_at
 * @property integer $type
 */
class ProductRelation extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id'], 'required'],
            [['product_id', 'relation_id', 'created_at', 'type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'relation_id' => 'Relation ID',
            'created_at' => 'Created At',
            'type' => 'Type',
        ];
    }

    /**
     * return product_id list 
     * @param type $product_id
     */
    public static function getProductIdInRel($product_id) {
        $products = (new \yii\db\Query())->select('*')
                ->from('product_relation')
                ->where('product_id=:product_id', [':product_id' => $product_id])
                ->all();
        return array_column($products, 'relation_id', 'relation_id');
    }

    public static function getDataRelationForProvider($product_id) {
        $products = (new \yii\db\Query())->select('*')
                ->from('product_relation')
                ->where('product_id=:product_id', [':product_id' => $product_id])
                ->all();
        return $products;
    }

}

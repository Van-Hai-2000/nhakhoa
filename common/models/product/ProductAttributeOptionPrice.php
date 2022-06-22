<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_attribute_option_price".
 *
 * @property string $id
 * @property string $product_id
 * @property integer $attribute_id
 * @property string $option_id
 * @property integer $change_price
 */
class ProductAttributeOptionPrice extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_attribute_option_price';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'attribute_id', 'option_id', 'change_price'], 'integer'],
            [['product_id', 'option_id'], 'unique', 'targetAttribute' => ['product_id', 'option_id'], 'message' => 'The combination of Product ID, Option ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'attribute_id' => 'Attribute ID',
            'option_id' => 'Option ID',
            'change_price' => 'Change Price',
        ];
    }

    public static function getByProduct($product_id) {
        $result = [];
        $data = ProductAttributeOptionPrice::find()->where('product_id=:product_id', [
                    ':product_id' => $product_id
                ])->all();
        //
        if (!empty($data)) {
            foreach ($data as $item) {
                $result[$item->attribute_id][$item->id] = $item;
            }
        }
        //
        return $result;
    }

    public static function getOptionProduct($product_id, $attribute_id) {
        $result = array();
        $rows = (new \yii\db\Query())->select('option_id, change_price')
                ->from('product_attribute_option_price')
                ->where('product_id=:product_id AND attribute_id=:attribute_id', [':product_id' => $product_id, ':attribute_id' => $attribute_id])
                ->all();
        if (!empty($rows)) {
            $price = array();
            $option_ids = array();
            foreach ($rows as $row) {
                $option_ids[] = $row['option_id'];
                $price[$row['option_id']] = $row['change_price'];
            }
            $option_ids = (!empty($option_ids)) ? '(' . implode(',', $option_ids) . ')' : '';
            $options = (new \yii\db\Query())->select('*')
                    ->from('product_attribute_option')
                    ->where('id IN ' . $option_ids)
                    ->orderBy('sort_order ASC')
                    ->all();
            if (!empty($options)) {
                foreach ($options as $item) {
                    $item['change_price'] = $price[$item['id']];
                    $result[] = $item;
                }
            }
        }
        return $result;
    }

    public static function getCountByProduct($product_id) {
        return (new \yii\db\Query())->select('*')
                        ->from('product_attribute_option_price')
                        ->where('product_id=:product_id', [':product_id' => $product_id])
                        ->column();
    }

    public static function getPrice($product_id, $option_id) {
        return (new \yii\db\Query())->select('*')
                        ->from('product_attribute_option_price')
                        ->where('product_id=:product_id AND option_id=:option_id', [':product_id' => $product_id, ':option_id' => $option_id])
                        ->scalar();
    }

}

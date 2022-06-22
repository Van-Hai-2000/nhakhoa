<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "map_price_formula".
 *
 * @property string $id
 * @property string $code_app
 * @property string $name
 * @property string $price_formula
 */
class MapPriceFormula extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'map_price_formula';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code_app', 'name', 'price_formula'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code_app' => 'Code App',
            'name' => 'Name',
            'price_formula' => 'Price Formula',
        ];
    }

}

<?php

namespace common\models\product;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_calculation_formula".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $percent
 * @property string $const_price
 * @property string $status
 * @property string $price
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductCalculationFormula extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_calculation_formula';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['percent', 'const_price', 'price'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Mô tả công thức tính',
            'percent' => 'Phần trăm',
            'const_price' => 'Công làm(Hằng số)',
            'status' => 'Trạng thái',
            'price' => 'Giá',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'gold_price' => 'Giá 1 chỉ vàng 9999',
        ];
    }

    public function beforeSave($insert) {
        $siteinfo = \common\components\ClaLid::getSiteinfo();
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->price = $this->const_price + ($this->percent * $siteinfo->gold_price) / 100 ;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @
     * return array options banner group
     */
    public static function optionsProductCalculationFormula() {
        $data = (new Query())->select('*')
            ->from('product_calculation_formula')
            ->all();
        return array_column($data, 'name', 'id');
    }
}

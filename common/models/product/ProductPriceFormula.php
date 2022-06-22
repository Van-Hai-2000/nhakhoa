<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_price_formula".
 *
 * @property string $id
 * @property string $code_app
 * @property string $name
 * @property string $formula_product
 * @property string $formula_gold
 * @property string $formula_fee
 * @property string $formula_stone
 * @property string $code_gold_parent
 * @property integer $id_currency
 * @property integer $status
 * @property string $description
 * @property string $coefficient1
 * @property string $coefficient2
 * @property string $coefficient3
 * @property string $coefficient4
 * @property string $coefficient5
 * @property string $coefficient6
 * @property string $coefficient7
 * @property string $coefficient8
 * @property string $coefficient9
 * @property string $coefficientm
 * @property string $coefficientx
 * @property string $created_at
 * @property string $updated_at
 */
class ProductPriceFormula extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_price_formula';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code_app', 'name'], 'required'],
            [['status', 'created_at', 'updated_at', 'id_currency'], 'integer'],
            [['description'], 'string'],
            [['coefficient1', 'coefficient2', 'coefficient3', 'coefficient4', 'coefficient5', 'coefficient6', 'coefficient7', 'coefficient8', 'coefficient9', 'coefficientm', 'coefficientx'], 'number'],
            [['code_app', 'name', 'code_gold_parent', 'formula_product', 'formula_gold', 'formula_fee', 'formula_stone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code_app' => 'Mã bên phần mềm',
            'name' => 'Tên quy tắc tính giá',
            'formula_product' => 'Công thức tính giá sản phẩm',
            'formula_gold' => 'Công thức tính giá vàng',
            'formula_fee' => 'Công thức tính tiền công',
            'formula_stone' => 'Công thức tính tiền đá',
            'code_gold_parent' => 'Mã vàng mẹ',
            'id_currency' => 'Mã tiền tệ',
            'status' => 'Trạng thái',
            'description' => 'Diễn giải',
            'coefficient1' => 'Hệ số 1',
            'coefficient2' => 'Hệ số 2',
            'coefficient3' => 'Hệ số 3',
            'coefficient4' => 'Hệ số 4',
            'coefficient5' => 'Hệ số 5',
            'coefficient6' => 'Hệ số 6',
            'coefficient7' => 'Hệ số 7',
            'coefficient8' => 'Hệ số 8',
            'coefficient9' => 'Hệ số 9',
            'coefficientm' => 'Hệ số m',
            'coefficientx' => 'Hệ số x',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            //
            return true;
        } else {
            return false;
        }
    }

}

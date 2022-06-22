<?php

namespace common\models\product;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_currency".
 *
 * @property string $id
 * @property string $code_app
 * @property string $name
 * @property string $price_sell
 * @property string $price_buy
 * @property integer $gold_yn
 * @property integer $money_yn
 * @property string $created_at
 * @property string $updated_at
 */
class ProductCurrency extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_currency';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code_app'], 'required'],
            [['price_sell', 'price_buy'], 'number'],
            [['gold_yn', 'money_yn', 'created_at', 'updated_at'], 'integer'],
            [['code_app', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code_app' => 'Mã bên phần mềm',
            'name' => 'Tên',
            'price_sell' => 'Giá bán',
            'price_buy' => 'Giá mua',
            'gold_yn' => 'Vàng',
            'money_yn' => 'Tiền',
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

    /**
     * @hungtm
     * return array options currency
     */
    public static function optionsCurrency() {
        $data = (new Query())->select('*')
                ->from('product_currency')
                ->all();
        return ['' => '------'] + array_column($data, 'name', 'code_app');
    }

    public static function getGoldIndex() {
        return (new Query())->select('*')
                ->from('product_currency')
                ->where(['id' => [2,5]])
                ->all();
    }

}

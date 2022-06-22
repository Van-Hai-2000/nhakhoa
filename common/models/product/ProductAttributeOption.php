<?php

namespace common\models\product;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_attribute_option".
 *
 * @property string $id
 * @property string $attribute_id
 * @property string $index_key
 * @property integer $sort_order
 * @property string $value
 * @property string $ext
 * @property string $code_app
 * @property string $field_app
 */
class ProductAttributeOption extends \yii\db\ActiveRecord {

    public static $_dataMulti = array(2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768, 65536, 131072, 262144, 524288, 1048576, 2097152, 4194304, 8388608, 16777216, 33554432, 67108864, 134217728, 268435456, 536870912, 1073741824, 2147483648);

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_attribute_option';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['attribute_id', 'index_key', 'sort_order'], 'integer'],
            [['value'], 'required'],
            [['value', 'ext', 'code_app', 'field_app'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'attribute_id' => 'Attribute ID',
            'index_key' => 'Index Key',
            'sort_order' => 'Order',
            'value' => 'Value',
            'ext' => 'Ext',
            'code_app' => 'Mã bên ứng dụng',
            'field_app' => 'Field bên ứng dụng'
        ];
    }

    public static function getOptionByAttribute($attribute_id) {
        return (new Query())->select('*')
                        ->from('product_attribute_option')
                        ->where('attribute_id=:attribute_id', [':attribute_id' => $attribute_id])
                        ->orderBy('sort_order ASC')
                        ->all();
    }

    public function generateKeyMulti($attribute_id) {
        if (!$attribute_id) {
            return 0;
        }
        $values = self::$_dataMulti;

        $valuesExist = (new Query())->select('index_key')
                ->from('product_attribute_option')
                ->where('attribute_id=:attribute_id', [':attribute_id' => $attribute_id])
                ->column();

        $valuesList = array_diff($values, $valuesExist);
        if (!empty($valuesList)) {
            return array_shift($valuesList);
        }
        return 0;
    }

    public static function getValueByKey($index_key, $attribute_id = 0) {
        if ($attribute_id) {
            return (new Query())->select('value')
                            ->from('product_attribute_option')
                            ->where('index_key=:index_key AND attribute_id=:attribute_id', [':index_key' => $index_key, ':attribute_id' => $attribute_id])
                            ->scalar();
        } else {
            return (new Query())->select('value')
                            ->from('product_attribute_option')
                            ->where('id=:id', [':id' => $index_key])
                            ->scalar();
        }
    }
    
    public static function getExtByKey($index_key, $attribute_id = 0) {
        if ($attribute_id) {
            return (new Query())->select('ext')
                            ->from('product_attribute_option')
                            ->where('index_key=:index_key AND attribute_id=:attribute_id', [':index_key' => $index_key, ':attribute_id' => $attribute_id])
                            ->scalar();
        } else {
            return (new Query())->select('ext')
                            ->from('product_attribute_option')
                            ->where('id=:id', [':id' => $index_key])
                            ->scalar();
        }
    }

}

<?php

namespace common\models\product;

use Yii;
use yii\db\Query;
use common\components\HtmlFormat;
use yii\helpers\Html;

/**
 * This is the model class for table "product_attribute".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $attribute_set_id
 * @property string $frontend_input
 * @property integer $type_option
 * @property integer $sort_order
 * @property string $default_value
 * @property integer $is_configurable
 * @property integer $is_filterable
 * @property integer $field_product
 * @property integer $is_system
 * @property integer $field_configurable
 * @property integer $is_children_option
 * @property integer $is_change_price
 * @property string $avatar_path
 * @property string $avatar_name
 */
class ProductAttribute extends \yii\db\ActiveRecord {

    const TYPE_OPTION_NONE = 0;
    const TYPE_OPTION_IMAGE = 1;
    const TYPE_OPTION_COLOR = 2;
    const TYPE_OPTION_CHANGE_PRICE = 3;
    
    public $avatar = '';

    public static $_dataFrontendInput = array(
        'text' => 'Text Field',
        'number' => 'Number',
        'textarea' => 'Text Area',
        'select' => 'Lựa chọn một giá trị',
        'multiselect' => 'Lựa chọn nhiều giá trị',
        'price' => 'Giá',
    );
    public static $_dataTypeOption = array(
        self::TYPE_OPTION_NONE => 'None',
        self::TYPE_OPTION_IMAGE => 'Image',
        self::TYPE_OPTION_COLOR => 'Color',
        self::TYPE_OPTION_CHANGE_PRICE => 'Kiểu đổi giá'
    );
    public static $_dataFieldDefine = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38);
    public static $_dataConfiguableDefine = array(1, 2, 3);
    
    public $attributeOption;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['attribute_set_id', 'type_option', 'sort_order', 'is_configurable', 'is_filterable', 'field_product', 'is_system', 'field_configurable', 'is_children_option', 'is_change_price'], 'integer'],
            [['frontend_input'], 'string'],
            [['name', 'code'], 'string', 'max' => 128],
            [['default_value', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
            [['avatar'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'code' => 'Mã',
            'attribute_set_id' => 'Nhóm thuộc tính',
            'frontend_input' => 'Loại thuộc tính',
            'type_option' => 'Kiểu giá trị mở rộng',
            'sort_order' => 'Sắp xếp',
            'default_value' => 'Giá trị mặc định',
            'is_configurable' => 'Cho phép đăng hoán vị',
            'is_filterable' => 'Hiển thị trong bộ lọc',
            'is_system' => 'Thuộc tính hệ thống',
            'field_configurable' => 'Vị trí field hoán vị',
            'is_children_option' => 'Cho phép tạo giá trị con',
            'is_change_price' => 'Cho phép thay đổi giá sản phẩm theo từng giá trị',
            'avatar' => 'Ảnh đại diện'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->sort_order == '') {
                $this->sort_order = 0;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @return type
     */
    public function getAttributeSet() {
        return $this->hasOne(ProductAttributeSet::className(), ['id' => 'attribute_set_id']);
    }

    public function getMaxOrder() {
        $max_order = (new Query())->select('MAX(sort_order)')
                ->from('product_attribute')
                ->scalar();
        return $max_order;
    }

    public function generateFieldConfigurable($attribute_set_id, $is_configurable, $frontend_input, $is_system = 0) {
        if ((!$attribute_set_id && !$is_system ) || !$is_configurable || ($frontend_input != 'multiselect' && $frontend_input != 'select')) {
            return 0;
        }
        //
        $fieldDefine = self::$_dataConfiguableDefine;
        $conditions = (($is_system) ? '' : ' (attribute_set_id=:attribute_set_id OR is_system=1)');
        $params = ($is_system) ? [] : [':attribute_set_id' => $attribute_set_id];
        $fieldExist = (new Query())->select('field_configurable')
                ->from('product_attribute')
                ->where($conditions, $params)
                ->column();
        //
        $fieldList = array_diff($fieldDefine, $fieldExist);
        if (!empty($fieldList)) {
            return array_shift($fieldList);
        }
        return 0;
    }

    public function generateFieldProduct($attribute_set_id, $frontend_input, $is_system = 0) {
        if ((!$attribute_set_id && !$is_system)) {
            return 0;
        }
        $fieldDefine = self::$_dataFieldDefine;
        $conditions = '1=1' . (($is_system) ? '' : ' AND (attribute_set_id=:attribute_set_id OR is_system=1)');
        $params = ($is_system) ? array() : array(':attribute_set_id' => $attribute_set_id);
        $fieldExist = (new Query())->select('field_product')
                ->from('product_attribute')
                ->where($conditions, $params)
                ->column();
        $fieldList = array_diff($fieldDefine, $fieldExist);
        if (!empty($fieldList)) {
            if ($frontend_input == 'multiselect') {
                return array_pop($fieldList);
            } elseif ($frontend_input == 'textnumber' || $frontend_input == 'price') {
                $arr = array(26, 25, 24, 23, 22, 21, 20, 19);
                $arr = array_intersect($arr, $fieldList);
                if (count($arr) > 0) {
                    return array_shift($arr);
                } else {
                    return array_shift($fieldList);
                }
            } else {
                return array_shift($fieldList);
            }
        }
        return 0;
    }
    
    public function getAttributeOption() {
        if (is_null($this->attributeOption)) {
            $condition = 'attribute_id = :attribute_id';
            $params = [
                ':attribute_id' => $this->id
            ];
            $this->attributeOption = ProductAttributeOption::find()->where($condition, $params)->all();
        }
        return $this->attributeOption;
    }

}

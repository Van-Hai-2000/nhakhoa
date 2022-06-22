<?php

namespace common\models\product;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_attribute_set".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 */
class ProductAttributeSet extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_attribute_set';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 128],
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
            'order' => 'Thứ tự',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian sửa',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            if ($this->code == '') {
                $this->code = \common\components\HtmlFormat::parseToAlias($this->name);
            }
            if ($this->order == '') {
                $this->order = 0;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @hungtm
     * return array options banner group
     */
    public static function optionsAttributeSet() {
        $data = (new Query())->select('*')
                ->from('product_attribute_set')
                ->all();
        return array_column($data, 'name', 'id');
    }

    public static function getAttributeConfigurable($att_set_id, $att_ids = []) {
        if (!$att_set_id) {
            return;
        }
        $where = (!empty($att_ids)) ? ' AND id IN(' . implode(',', $att_ids) . ')' : '';
        $return = ProductAttribute::find()
                ->where('(attribute_set_id=' . $att_set_id . ' OR is_system=1) AND is_configurable=1 AND field_configurable>0' . $where)
                ->orderBy('sort_order ASC')
                ->all();
        return $return;
    }

    public static function getAttributeChangePrice($att_set_id) {
        $data = ProductAttribute::find()->where('(attribute_set_id=:attribute_set_id OR is_system=1) AND is_change_price=1', [
                    ':attribute_set_id' => $att_set_id
                ])->all();
        return $data;
    }
    
    public static function getAttributesBySet($attribute_set_id, $condition = '', $params = array(), $order = 'sort_order asc') {
        $results = array();
        $where = 'attribute_set_id=:attribute_set_id';
        $condition = ($condition) ? $where . ' AND ' . $condition : $where;
        $params = array_merge($params, array(':attribute_set_id' => $attribute_set_id));
        $data = (new Query())->select('*')
                ->from('product_attribute')
                ->where($condition, $params)
                ->orderBy($order)
                ->all();
        if (count($data)) {
            foreach ($data as $item) {
                $results[$item['id']] = $item;
            }
        }
        return $results;
    }

}

<?php

namespace backend\components;

use common\components\HtmlFormat;
use common\models\product\ProductAttributeOption;
use yii\helpers\Html;
use yii\db\Query;

class ProductCategoryAttributeHelper {

    /**
     * Render html attribute set in product admin 
     * 
     */
    public static function attRenderHtmlAll($attribute_set_id, $category = null) {
        $values = ($category) ? self::getDynamicValueProduct($category) : array();
        //
        $result = '<div class="product-attributes">';
        //
        $dataReader = (new Query())->select('*')
                ->from('product_attribute')
                ->where('attribute_set_id=:attribute_set_id AND is_change_price = 0', [
                    ':attribute_set_id' => $attribute_set_id
                ])
                ->orderBy('sort_order ASC')
                ->all();
        //
        foreach ($dataReader as $row) {
            if (!empty($row) && ($row['name'])) {
                if (isset($values[$row['id']])) {
                    $row['value'] = $values[$row['id']];
                }
                if (($row['frontend_input'] == 'select') || ($row['frontend_input'] == 'multiselect')) {
                    $dataArray = ProductAttributeOption::find()
                            ->where('attribute_id=:attribute_id', [':attribute_id' => $row['id']])
                            ->asArray()
                            ->all();
                    $data = array_column($dataArray, 'value', 'index_key');
                    $htmlOption = array('class' => 'form-control');
                    $result .= self::attInputTemplate($row, $htmlOption, $data);
                } else {
                    $result .= self::attInputTemplate($row, array('class' => 'form-control'));
                }
            }
        }
        $result .= '</div>';
        return $result;
    }

    public static function getDynamicValueProduct($product) {
        $values = array();
        $valueAtt = json_decode($product->dynamic_field);
        if (!empty($valueAtt)) {
            foreach ($valueAtt as $att) {
                $values[$att->id] = $att->value;
            }
        }
        return $values;
    }

    /*
     * Input template html
     */

    public static function attInputTemplate($attribute, $htmlOptions = array(), $data = array()) {
        $output = '';
        $html = self::attRenderInputHtml($attribute, $htmlOptions, $data);
        $htm_new = '';
        if (($attribute['frontend_input'] == 'select') || ($attribute['frontend_input'] == 'multiselect')) {
            //if ($attribute['frontend_input'] == 'select') {
            $htm_new = '<div class="new_attr input-group-btn" style="padding-left:20px;">
                           <input type="button" class="is_newattr btn btn-primary btn-sm" style="line-height:14px;" onclick="is_newattr_click(this,' . $attribute['id'] . ')"  id="is_newattr_' . $attribute['id'] . '" lang="' . $attribute['id'] . '" value="Thêm giá trị" />
                            <div class="new_attr_conten" id="new_attr_conten_' . $attribute['id'] . '" style="display:none">
                                <input type="text" value="" id="attribute_option_' . $attribute['id'] . '" name="attribute_option_' . $attribute['id'] . '" placeholder="Giá trị mới">                                
                                <input class="btn btn-xs btn-primary" type="button" value="Lưu" onclick="new_attr(' . $attribute['id'] . ')"/>
                                <input class="btn btn-xs btn-danger" type="button" value="Hủy" onclick="close_new_attr(' . $attribute['id'] . ')"/>
                            </div>
                            <div style="display: none;" class="tree-loader att-loading-' . $attribute['id'] . '"><div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div></div>
                        </div>';
        }
        if (!empty($html)) {
            $class = ($attribute['is_configurable']) ? 'is-att-cf' : '';
            if (empty($class)) {
                $output .= '<div class="control-group form-group row-att-' . $attribute['id'] . ' ' . $class . '">';
                $output .='<label class="col-sm-2 control-label no-padding-left" for="Atribute_' . $attribute['id'] . '">' . $attribute['name'] . '</label>';
                $output .= '<div class="col-md-10 col-sm-10 col-xs-12">' . $html . '</div>';
                $output .= "</div>";
            }
        }
        return $output;
    }

    public static function attRenderInputHtml($attribute, $htmlOptions = array(), $data = array()) {
        $attribute['value'] = !isset($attribute['value']) ? $attribute['default_value'] : $attribute['value'];
        $html = '';
        $beforeHtml = '';
        $afterHtml = '';
        switch ($attribute['frontend_input']) {
            case 'text':
            case 'price':
            case 'number':
                $html = Html::textInput('Attribute[' . $attribute['id'] . ']', $attribute['value'], $htmlOptions);
                break;
            case 'textarea':
                $htmlOptions = array_merge($htmlOptions, array('rows' => 4));
                $html = Html::textarea('Attribute[' . $attribute['id'] . ']', $attribute['value'], $htmlOptions);
                break;
            case 'select':
                $htmlOptions = array_merge($htmlOptions, array(
                    'prompt' => '-- Hãy chọn--',
                    'class' => 'select-two form-control',
                    'id' => 'Attribute_' . $attribute['id'],
                    'multiple' => 'multiple'
                ));
                $html = Html::dropDownList('Attribute[' . $attribute['id'] . ']', $attribute['value'], $data, $htmlOptions);
                break;
            case 'multiselect':
                $htmlOptions = array('multiple' => 'multiple', 'size' => 6, 'class' => 'span12 col-sm-4');
                $html = Html::dropDownList('Attribute[' . $attribute['id'] . '][]', $attribute['value'], $data, $htmlOptions);
                break;
            default:
                $html = '';
        }

        return $beforeHtml . $html . $afterHtml;
    }

}

<?php

/**
 */
class EconomyAttributeHelper extends CComponent {

    public static $_helper;

    public static function helper($isNew = false) {
        if (!is_null(self::$_helper) && !$isNew)
            return self::$_helper;
        else {
            $className = __CLASS__;
            $helper = self::$_helper = new $className();
            return $helper;
        }
    }

    /**
     * Render html attribute set in product admin 
     * 
     */
    public function attRenderHtmlAll($attribute_set_id, $product_info = null) {
        $values = ($product_info) ? $this->getDynamicValueProduct($product_info) : array();
        $result = '<div class="product-attributes">';
        $dataReader = Yii::app()->db->createCommand()
                ->select('*')
                ->from('product_attribute')
                ->where('(attribute_set_id=:attribute_set_id OR is_system=1)', array(':attribute_set_id' => $attribute_set_id))
                ->order('is_system desc,sort_order asc')
                ->query();
        foreach ($dataReader as $row) {
            if (!empty($row) && ($row['name'])) {
                if (isset($values[$row['id']])) {
                    $row['value'] = $values[$row['id']];
                    if(isset($values['child'][$row['id']])){
                        $row['value_child'] = $values['child'][$row['id']];
                    }
                }
                if (($row['frontend_input'] == 'select') || ($row['frontend_input'] == 'multiselect')) {
                    $dataArray = ProductAttributeOption::model()->findAllByAttributes(
                            array(), 'attribute_id = :attribute_id ORDER BY sort_order', array(':attribute_id' => $row['id']));
                    $data = CHtml::listData(
                                    $dataArray, 'index_key', 'value'
                    );
                    $htmlOption = array('class' => 'span12 col-sm-4');
                    $result .= $this->attInputTemplate($row, $htmlOption, $data);
                } else {
                    $result .= $this->attInputTemplate($row, array('class' => 'span12 col-sm-10'));
                }
            }
        }
        $result .= '</div>';
        return $result;
    }

    public function getDynamicValueProduct($productInfo) {
        $values = array();  
        $values['child'] = array();
        $valueAtt = json_decode($productInfo->dynamic_field);
        if (!empty($valueAtt)) {
            foreach ($valueAtt as $att) {
                $values[$att->id] = $att->value;      
                if(isset($att->value_child)){
                    $values['child'][$att->id] = $att->value_child;
                }
            }
        }
        return $values;
    }

    /*
     * Render input html attribute product admin
     */

    public function attRenderInputHtml($attribute, $htmlOptions = array(), $data = array()) {
        $attribute['value'] = !isset($attribute['value']) ? $attribute['default_value'] : $attribute['value'];
        $html = '';
        $beforeHtml = '';
        $afterHtml = '';
        switch ($attribute['frontend_input']) {
            case 'text':
            case 'date':
            case 'number':
                $html = CHtml::textField('Attribute[' . $attribute['id'] . ']', $attribute['value'], $htmlOptions);
                break;
            case 'textnumber':
                $htmlOptions = array_merge($htmlOptions, array('class' => 'isnumber span12 col-sm-4'));
                $html = CHtml::textField('Attribute[' . $attribute['id'] . ']', $attribute['value'], $htmlOptions);
                break;
            case 'price':
                $attribute['value'] = HtmlFormat::money_format(floatval($attribute['value']));
                $htmlOptions = array_merge($htmlOptions, array('class' => 'numberFormat span12 col-sm-4'));
                $html = CHtml::textField('Attribute[' . $attribute['id'] . ']', $attribute['value'], $htmlOptions);
                break;
            case 'textarea':
                $htmlOptions = array_merge($htmlOptions, array('rows' => 6, 'cols' => 60, 'class' => 'span12 col-sm-10'));
                $html = CHtml::textArea('Attribute[' . $attribute['id'] . ']', $attribute['value'], $htmlOptions);
                break;
            case 'select':
                $classCss = ($attribute['is_children_option']) ? ' has_children_option' : '';
                $htmlOptions = array_merge($htmlOptions, array('empty' => '-- Hãy chọn--', 'class' => 'span12 col-sm-4' . $classCss));
                $html = CHtml::dropDownList('Attribute[' . $attribute['id'] . ']', $attribute['value'], $data, $htmlOptions);
                break;
            case 'multiselect':
                $htmlOptions = array('multiple' => 'multiple', 'size' => 6, 'class' => 'span12 col-sm-4');
                $html = CHtml::dropDownList('Attribute[' . $attribute['id'] . '][]', $attribute['value'], $data, $htmlOptions);
                break;
            default:
                $html = '';
        }

        return $beforeHtml . $html . $afterHtml;
    }

    /*
     * Input template html
     */

    public function attInputTemplate($attribute, $htmlOptions = array(), $data = array()) {
        $output = '';
        $html = $this->attRenderInputHtml($attribute, $htmlOptions, $data);
        $htm_new = '';
        if (($attribute['frontend_input'] == 'select') || ($attribute['frontend_input'] == 'multiselect')) {                        
            $htm_new = '<div class="new_attr input-group-btn" style="padding-left:20px;">
                           <input type="button" class="is_newattr btn btn-primary btn-sm" style="line-height:14px;" onclick="is_newattr_click(this,' . $attribute['id'] . ')"  id="is_newattr_' . $attribute['id'] . '" lang="' . $attribute['id'] . '" value="Thêm giá trị" />
                            <div class="new_attr_conten" id="new_attr_conten_' . $attribute['id'] . '" style="display:none">
                                <input type="text" value="" id="attribute_option_' . $attribute['id'] . '" name="attribute_option_' . $attribute['id'] . '" placeholder="Giá trị mới">                                
                                <input class="btn btn-xs btn-primary" type="button" value="Lưu" onclick="new_attr(' . $attribute['id'] . ')"/>
                                <input class="btn btn-xs btn-danger" type="button" value="Hủy" onclick="close_new_attr(' . $attribute['id'] . ')"/>
                            </div>
                            <div style="display: none;" class="tree-loader att-loading-' . $attribute['id'] . '"><div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div></div>
                        </div>';
//            <input type="text" value="" id="attribute_option_code' . $attribute['id'] . '" name="attribute_option_code' . $attribute['id'] . '" placeholder="Mã thuộc tính">
            if ($attribute['frontend_input'] == 'select' && (int)$attribute['is_children_option']) {
               $htm_new .= '<div lang="'.(isset($attribute['value_child'])?$attribute['value_child']:'').'" class="contain_option_children" id="contain_option_children_'.$attribute['id'].'" style="clear:both;margin-top:10px;"></div>'; 
            }
        }
        if (!empty($html)) {
            $class = ($attribute['is_configurable']) ? 'is-att-cf' : '';
            if (empty($class)) {
                $output .= '<div class="control-group form-group row-att-' . $attribute['id'] . ' ' . $class . '">';
                $output .='<label class="col-sm-2 control-label no-padding-left" for="Atribute_' . $attribute['id'] . '">' . $attribute['name'] . '</label>';
                $output .= '<div class="controls col-sm-10">' . $html . $htm_new . '</div>';
                $output .= "</div>";
            }
        }
        return $output;
    }

    public function updateValueDynamic($values, $dynamic) {
        $dynamic = json_decode($dynamic, true);
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                //key is attribute id
                if (count($dynamic)) {
                    $exist = false;
                    $c_dynamic = count($dynamic);
                    for ($i = 0; $i < $c_dynamic; $i++) {
                        if (isset($dynamic[$i]['id']) && $dynamic[$i]['id'] == $key) {
                            $dynamic[$i]['index_key'] = $value;
                            $dynamic[$i]['value'] = $value;
                            $exist = true;
                        }
                    }
                    if (!$exist && count($value)) {
                        $modelAtt = ProductAttribute::model()->findByPk($key);
                        if ($modelAtt) {
                            $dynamic[$c_dynamic]['id'] = $key;
                            $dynamic[$c_dynamic]['name'] = $modelAtt->name;
                            $dynamic[$c_dynamic]['code'] = $modelAtt->code;
                            $dynamic[$c_dynamic]['index_key'] = $value;
                            $dynamic[$c_dynamic]['value'] = $value;
                        }
                    }
                }
            }
        }
        return json_encode($dynamic);
    }

    public function updateValueAttProduct($values, $model) {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                //key is attribute id
                $att = ProductAttribute::model()->findByPk($key);
                if ($att && $att->field_product) {
                    $field = "cus_field" . $att->field_product;
                    $model->$field = array_sum($value);
                }
            }
        }
    }

}

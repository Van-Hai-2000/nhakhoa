<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ques_ans}}".
 *
 * @property string $id
 * @property string $question
 * @property string $answer
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $cat_id
 */
class ActiveFormC extends \yii\widgets\ActiveForm
{
  
    public $class='group-input';
    public $id;
    // public $name;
    public $model;
    public $attribute;
    public $label;
    public $arrSelect = [];
    public $errors;

    public function textInput($options = []){
        echo $this->field($this->model, $this->attribute , [
            'template' => '<div class="item-input-form">{label}<div class="'.$this->class.'"><div class="full-input">{input}{error}<p class="skip">'.(isset($options['skip']) ? $options['skip'] : '').'</p>{hint}</div></div></div>'
        ])->textInput($options)->label($this->label, [
            'class' => '',
        ]);
    }

    public function textArea($options = []){
        echo $this->field($this->model, $this->attribute , [
            'template' => '<div class="item-input-form">{label}<div class="'.$this->class.'"><div class="full-input">{input}{error}{hint}</div></div></div>'
        ])->textArea($options)->label($this->label, [
            'class' => '',
        ]);
    }

    public function textSelect($options = []){
        $class = isset($options['class']) ? $options['class'] :  $this->attribute;
        $id = isset($options['id']) ? $options['id'] :  $this->attribute;
        echo $this->field($this->model, $this->attribute, [
             'template' => '<div class="item-input-form">{label}<div class="'.$this->class.'"><div class="full-input">{input}{error}{hint}</div></div></div>'
        ])->dropDownList($this->arrSelect, [
            'class' => $class,
            'id' => $id,
        ])->label($this->label, [
            'class' => ''
        ]);
    }

    public function textSelectMultiple($options = []){
        $class = isset($options['class']) ? $options['class'] :  $this->attribute;
        $id = isset($options['id']) ? $options['id'] :  $this->attribute;
        echo '<script>
            jQuery(document).ready(function () {jQuery("#'.$this->attribute.'").select2({
                    maximumSelectionLength: 63,
                    placeholder: "'.$this->arrSelect[''].'",
                    allowClear: true
                });
                
            });
        </script>';
        unset($this->arrSelect['']);
        echo $this->field($this->model, $this->attribute, [
             'template' => '<div class="item-input-form">{label}<div class="'.$this->class.'"><div class="full-input">{input}{error}{hint}</div></div></div>'
        ])->dropDownList($this->arrSelect, [
            'class' => $class,
            'id' => $id,
            'multiple' => 'multiple',
        ])->label($this->label, [
            'class' => ''
        ]);
    }

    public function fields($model, $attribute, $options = [])
    {
        $this->model = $model;
        // $this->name = $this->getName($model, $attribute);
        $this->attribute = $attribute;
        $this->class = isset($options['class']) ? $options['class'] : $this->class;
        $this->id = isset($options['id']) ? $options['id'] :  '';
        $this->arrSelect = isset($options['arrSelect']) ? $options['arrSelect'] :  [];
        $this->label = $model->getAttributeLabel($attribute);
        $this->errors = (isset($model['_errors'][$attribute][0]) && $model['_errors'][$attribute][0]) ? $model['_errors'][$attribute][0] : '';
        return $this;
    }

    public function getName($model, $attribute) {
        $name = $model->tableName();
        $name = str_replace('{{%', '', $name);
        $name = str_replace('}}', '', $name);
        $name[0] = strtoupper($name[0]);
        $name .= '['.$attribute.']';
        return $name;
    }

    public function getAttrName() {
        return isset($this->model->attributeLabels()[$this->attribute]) ? $this->model->attributeLabels()[$this->attribute] : $this->attribute;
    }

    public function getClassName() {
        $string = get_class($this->model);
        $string = explode('\\', $string);
        return $string[count($string) -1];
    }

}

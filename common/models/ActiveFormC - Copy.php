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
  
    public $class='item-input-form';
    public $id;
    public $name;
    public $model;
    public $attribute;
    public $label;
    public $arrSelect = [];
    public $errors;

    public function textInput($options = []){
        $class = isset($options['class']) ? $options['class'] :  $this->attribute;
        $type = isset($options['type']) ? $options['type'] : 'text';
        $id = isset($options['id']) ? $options['id'] :  $this->attribute;
        $placeholder = isset($options['placeholder']) ? $options['placeholder'] :  'Nhập '.$this->label;
        echo '<div class="'.$this->class.'" id="'.$this->id.'">
            <label for="">'.$this->label.'</label>
            <div class="group-input">
                <div class="full-input">
                    <input type="'.$type.'"  name="'.$this->name.'" value="'.$this->model[$this->attribute].'"  class="'.$class.'" id="'.$id.'" placeholder="'.$placeholder.'">
                    '.(($this->errors) ? '<div class="error">'.$this->errors.'</div>' : '' ).'
                </div>
            </div>
        </div>';
    }

    public function textSelect($options = []){
        $class = isset($options['class']) ? $options['class'] :   $this->attribute;
        $id = isset($options['id']) ? $options['id'] :  $this->attribute;
        echo '<div class="'.$this->class.'" id="'.$this->id.'">
            <label for="">'.$this->label.'</label>
            <div class="group-input">
                <div class="full-input"><select name="'.$this->name.'" id="'.$id.'" class="'.$class.'">';
        foreach ($this->arrSelect as $key => $value) {
            echo '<option value="'.$key.'">'.$value.'</option>';
        }           
        echo '</select>
             '.(($this->errors) ? '<div class="error">'.$this->errors.'</div>' : '' ).'
                </div>
            </div>
        </div>';
    }

    public function field($model, $attribute, $options = [])
    {
        $this->model = $model;
        $this->name = $this->getName($model, $attribute);
        $this->attribute = $attribute;
        $this->class = isset($options['class']) ? $options['class'] : $this->class;
        $this->id = isset($options['id']) ? $options['id'] :  '';
        $this->arrSelect = isset($options['arrSelect']) ? $options['arrSelect'] :  [];
        $this->label = $model->getAttributeLabel($attribute);
        $this->errors = (isset($model['_errors'][$attribute][0]) && $model['_errors'][$attribute][0]) ? $model['_errors'][$attribute][0] : '';
        return $this;
    }

    private function getName($model, $attribute) {
        $name = $model->tableName();
        $name = str_replace('{{%', '', $name);
        $name = str_replace('}}', '', $name);
        $name[0] = strtoupper($name[0]);
        $name .= '['.$attribute.']';
        return $name;
    }

}

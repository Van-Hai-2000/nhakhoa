<?php

namespace common\components;

use Yii;
use yii\helpers\Html;

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

    public $class = 'group-input';
    public $id;
    // public $name;
    public $model;
    public $attribute;
    public $label;
    public $arrSelect = [];
    public $errors;
    private $_save = null;


    public static function begin1($options = [])
    {
        $options['options']['class'] = isset($options['options']['class']) ? 'form_save_data ' . $options['options']['class'] : 'form_save_data';
        $title_form = isset($options['options']['title_form']) ? $options['options']['title_form'] : '';
        $form = parent::begin($options);
        if ($title_form) {
            echo '<div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> ' . $title_form . ' </h2>
                    <div class="clearfix"></div>
                </div>';
        }
        return $form;
    }
    public static function beginSearcAjax($options = [])
    {
        $title_form = isset($options['options']['title_form']) ? $options['options']['title_form'] : Yii::t('app', 'title_form_empty');
        $form = parent::begin($options);
        echo '';
        return $form;
    }
    public static function end1($options)
    {
        $title = Yii::t('app', 'create');
        $class = 'btn btn-success';
        if (isset($options['update'])  && $options['update']) {
            $title = Yii::t('app', 'update');
            $class = 'btn btn-primary';
        }
        if (isset($options['title'])  && $options['title']) {
            $title = $options['title'];
        }
        if (isset($options['view']) && $options['view']) {
            echo '<div class="form-group center">';
            // echo '<a class="btn btn-primary"  href="update.html?id='.$_GET['id'].'">' . Yii::t('app', 'to_update') . '</a>';
            echo '<a class="go-back" onclick="window.history.back();">' . Yii::t('app', 'back') . '</a>';
            // echo '<a class="btn btn-danger" data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post"  href="delele.html?id='.$_GET['id'].'">' . Yii::t('app', 'delete') . '</a>';
            echo '</div>';
        } else {
            echo '</div>';
            echo '<div class="form-group center">';
            echo Html::submitButton($title, ['class' => $class]);
            echo '<a class="go-back" onclick="window.history.back();">' . Yii::t('app', 'back') . '</a>';
            echo '</div>';
        }
        $end = parent::end();
        return $end;
    }
    public function fieldF($model, $attribute, $options = [])
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $options = $options ? $options : ['template' => '<div class="item-input-form">{label}<div class="group-input">{input}{error}</div></div>'];
        $this->_save = $this->field($model, $attribute, $options);
        return $this;
    }

    public function labelF($label = null, $options = [])
    {
        if ($this->_save) {
            $label = $label ?  $label : ($label == '0') ? '' : $this->getAttrName();
            $options = $options ? $options : ['class' => ''];
            return $this->_save->label($label, $options);
        } else {
            return parent::label($label, $options);
        }
    }
    public function field1($model, $attribute, $options = [])
    {
        $class = isset($model->_view_form) ? 'item-input-form disabled_form' : 'item-input-form';
        $this->model = $model;
        $this->attribute = $attribute;
        $options = $options ? $options : ['template' => '{label}<div class="' . $class . '">{input}{error}{hint}</div>'];
        $this->_save = $this->field($model, $attribute, $options);
        return $this;
    }
    public function fieldB($model, $attribute, $options = [])
    {
        $class = isset($model->_view_form) ? 'item-input-form disabled_form' : 'col-md-10 col-sm-10 col-xs-12';
        $this->model = $model;
        $this->attribute = $attribute;
        $options = $options ? $options : ['template' => '{label}<div class="' . $class . '">{input}{error}{hint}</div>'];
        $this->_save = $this->field($model, $attribute, $options);
        return $this;
    }
    public function fieldS($model, $attribute, $options = [])
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $options = $options ? $options : ['template' => '{input}'];
        $this->_save = $this->field($model, $attribute, $options);
        return $this;
    }
    public function radio($options = [])
    {
        if ($this->_save) {
            if (isset($options['arr_select']) && $options['arr_select']) {
                return $this->render('activeformc/_radio', ['model' => $this, 'options' => $options]);
            }
        }
        return parent::radio('');
    }
    public function textInput($options = [])
    {
        if ($this->_save) {
            if (isset($options['placeholder']) && $options['placeholder']) {
                $this->_save = $this->_save->textInput($options);
            } else {
                $options['placeholder'] = $this->getAttrName();
                $this->_save = $this->_save->textInput($options);
            }
            return $this;
        } else {
            return parent::textInput($options);
        }
    }
    public function textInputMoney($options = [])
    {
        $options['id'] = "money-input-" . $this->attribute;
        $options['class'] = isset($options['class']) ? $options['class'] . " input-money none-name " . $options['class'] : "form-control input-money none-name";
        echo $this->render('activeformc/_textMoney', ['model' => $this, 'options' => $options]);
        $this->model[$this->attribute] = ($this->model[$this->attribute] > 0) ? \common\components\ClaLid::formatMoney($this->model[$this->attribute]) : '';
        if ($this->_save) {
            if (isset($options['placeholder']) && $options['placeholder']) {
                $this->_save = $this->_save->textInput($options);
            } else {
                $options['placeholder'] = $this->getAttrName();
                $this->_save = $this->_save->textInput($options);
            }
            return $this;
        } else {
            return parent::textInput($options);
        }
    }
    public function textFindAjax($options)
    {
        return $this->render('activeformc/_textFindAjax', ['model' => $this, 'options' => $options]);
    }
    public function textFindAjaxf($options)
    {
        if ($this->_save) {
            if (isset($options['placeholder']) && $options['placeholder']) {
                $this->_save = $this->_save->textInput($options);
            } else {
                $options['placeholder'] = $this->getAttrName();
                $this->_save = $this->_save->textInput($options);
            }
            // echo $this->render('activeformc/_textFindAjaxf', ['model' => $this, 'options' => $options]);
            if (isset($options['type']) && $options['type'] = 'rank') {
                echo $this->render('activeformc/_textFindAjaxfRank', ['model' => $this, 'options' => $options]);
            } else {
                echo $this->render('activeformc/_textFindAjaxf', ['model' => $this, 'options' => $options]);
            }
            return $this;
        } else {
            return parent::textInput($options);
        }
    }
    public function textFindAjaxfByDiv($options)
    {
        if ($this->_save) {
            if (isset($options['placeholder']) && $options['placeholder']) {
                $this->_save = $this->_save->textInput($options);
            } else {
                $options['placeholder'] = $this->getAttrName();
                $this->_save = $this->_save->textInput($options);
            }
            echo $this->render('activeformc/_textFindAjaxfByDiv', ['model' => $this, 'options' => $options]);
            return $this;
        } else {
            return parent::textInput($options);
        }
    }
    public function textPassword($options = [])
    {
        if ($this->_save) {
            if ($options) {
                $options['type'] = 'password';
                $this->_save = $this->_save->textInput($options);
            } else {
                $options = [
                    'type' => 'password',
                    'placeholder' => $this->getAttrName()
                ];
                $this->_save = $this->_save->textInput($options);
            }
            return $this;
        } else {
            return parent::textInput($options);
        }
    }
    public function checkBox($options = [])
    {
        if ($this->_save) {
            $options['class'] = 'js-switch';
            $this->_save = $this->_save->checkBox($options);
            return $this;
        } else {
            return parent::checkBox($options);
        }
    }
    public function textArea($options = [])
    {
        if ($this->_save) {
            if ($options) {
                $options['type'] = 'password';
                $this->_save = $this->_save->textArea($options);
            } else {
                $options = [
                    'type' => 'password',
                    'placeholder' => $this->getAttrName()
                ];
                $this->_save = $this->_save->textArea($options);
            }
            return $this;
        } else {
            return parent::textArea($options);
        }
    }
    public function textDate($options = [])
    {
        if ($this->_save) {
            $format = isset($options['format']) ? $options['format'] : 'DD-MM-YYYY';
            $last = isset($options['last']) ? $options['last'] : 0;
            $single = isset($options['single']) ? $options['single'] : true;
            $options = [
                'placeholder' => $this->getAttrName(),
                'id' => 'input-date-' . $this->attribute,
                'class' => 'form-control date',
                'autocomplete' => 'off'
            ];
            $this->_save = $this->_save->textInput($options);
            echo $this->render('activeformc/_date', ['model' => $this, 'format' => $format, 'last' => $last, 'single' => $single]);

            return $this;
        } else {
            return parent::textInput($options);
        }
    }

    public function dropDownList($arr = [], $options = [])
    {
        if ($this->_save) {
            if ($options) {
                $this->_save = $this->_save->dropDownList($arr, $options);
            } else {
                $this->_save = $this->_save->dropDownList($arr, $options);
            }
            return $this;
        } else {
            return parent::dropDownList($arr, $options);
        }
    }


    public function label($label = null, $options = [])
    {
        if ($this->_save) {
            $label = $label ?  $label : ($label == '0') ? '' : $this->getAttrName();
            $options = $options ? $options : ['class' => 'control-label col-md-2 col-sm-2 col-xs-12',];
            return $this->_save->label($label, $options);
        } else {
            return parent::label($label, $options);
        }
    }

    public function hiddenInput()
    {
        if ($this->_save) {
            $label = $label ?  $label : ($label == '0') ? '' : $this->getAttrName();
            $options = $options ? $options : ['class' => 'control-label col-md-2 col-sm-2 col-xs-12',];
            return $this->_save->label($label, $options);
        } else {
            return parent::hiddenInput();
        }
    }

    public function getName($model, $attribute)
    {
        $name = $model->tableName();
        $name = str_replace('{{%', '', $name);
        $name = str_replace('}}', '', $name);
        $name[0] = strtoupper($name[0]);
        $name .= '[' . $attribute . ']';
        return $name;
    }

    public function getAttrName()
    {
        return isset($this->model->attributeLabels()[$this->attribute]) ? $this->model->attributeLabels()[$this->attribute] : $this->attribute;
    }

    public function getClassName()
    {
        $string = get_class($this->model);
        $string = explode('\\', $string);
        return $string[count($string) - 1];
    }
}

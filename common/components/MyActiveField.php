<?php

namespace common\components;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

class MyActiveField extends ActiveField
{

    public $labelOptions = ['class' => 'form-label semibold'];

    public function init()
    {
//        $position = ArrayHelper::remove($this->options, 'right');
//
//        $icon = $this->_setFieldIcon($this->options);

        $this->template = '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>';

        $this->labelOptions = ['class' => 'control-label col-md-2 col-sm-2 col-xs-12'];
//        $this->label = '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>';

        parent::init();
    }

    /**
     * @param $option array
     * @return string HTML
     */
//    private function _setFieldIcon($option)
//    {
//        $icon = '';
//        switch (ArrayHelper::getValue($option, 'icon', '')) {
//            case 'text':
//                $icon = '<i class="fa fa-text-width"></i>';
//                break;
//            case 'password':
//                $icon = '<i class="fa fa-key" aria-hidden="true"></i>';
//                break;
//        }
//
//        return $icon;
//    }
}
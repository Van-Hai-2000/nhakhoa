<?php

namespace backend\widgets\form;

use yii\base\Widget;

class FormWidget extends Widget {

    public $input = [];
    public $view = 'view';
   
    public function init() {
        parent::init();
    }
    public function run() {
        return $this->render($this->view, $this->input);
    }

}

?>
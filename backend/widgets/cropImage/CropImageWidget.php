<?php

namespace backend\widgets\cropImage;

use yii\base\Widget;
use common\models\product\Product;

class CropImageWidget extends Widget {

    public $view = 'view';
    public $input = [];

    public function init() {
        parent::init();
    }

    public function run() {
        //
        return $this->render($this->view, $this->input);
    }

}

?>
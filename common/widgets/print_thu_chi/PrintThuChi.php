<?php

namespace common\widgets\print_thu_chi;

use Yii;
use yii\base\Widget;

class PrintThuChi extends Widget
{
    public $view = 'view';
    public $data = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render($this->view, array(
            'data' => $this->data,
        ));
    }

}

?>
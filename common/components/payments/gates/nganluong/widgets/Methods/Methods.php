<?php

namespace common\components\payments\gates\nganluong\widgets\Methods;

use yii\base\Widget;
use yii\helpers\Html;
use common\components\payments\gates\nganluong\helpers\NganluongBankHelper;

/**
 * Description of Methods
 *
 * @author minhbn
 */
class Methods extends Widget {

    public $methods = [];

    public function init() {
        parent::init();
        $this->methods['banks'] = NganluongBankHelper::optionBankAtmOnline();
        $this->methods['visas'] = array(
            'VISA' => 'Visa',
            'MASTER' => 'Master',
        );
    }

    //
    public function run() {
        return $this->render('view', array(
            'methods' => $this->methods,
        ));
    }

}

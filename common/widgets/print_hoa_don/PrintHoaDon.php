<?php

namespace common\widgets\print_hoa_don;

use common\models\user\MedicalRecord;
use common\models\user\MedicalRecordChild;
use common\models\user\MedicalRecordItemChild;
use Yii;
use yii\base\Widget;

class PrintHoaDon extends Widget
{
    public $view = 'view';
    public $medical_record_id;
    public $data = [];

    public function init()
    {
        $this->data = $this->getData();
        parent::init();
    }

    public function run()
    {
        return $this->render($this->view, array(
            'data' => $this->data,
            'medical_record_id' => $this->medical_record_id,
        ));
    }

    function getData(){
        $medical_record = MedicalRecord::find()->where(['medical_record.id' => $this->medical_record_id])->joinWith(['user'])->asArray()->one();
        $medical_record_child = MedicalRecordItemChild::find()->where(['medical_record_id' => $this->medical_record_id])->joinWith(['product'])->asArray()->all();
        $chua_kham = MedicalRecordChild::find()->where(['medical_record_id' => $this->medical_record_id])->joinWith(['product'])->asArray()->all();

        return [
            'hoso' => $medical_record,
            'products' => $medical_record_child,
            'chua_kham' => $chua_kham,
        ];
    }

}

?>
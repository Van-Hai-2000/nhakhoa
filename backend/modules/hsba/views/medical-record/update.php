<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecord */

$this->title = 'Cập nhật hồ sơ: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hồ sơ bệnh án', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medical-record-update">

    <?= $this->render('_form_update', [
        'model' => $model,
        'categories' => $categories,
        'medical_record_child' => $medical_record_child,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\medical_record\MedicalRecordLog */

$this->title = 'Update Medical Record Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Medical Record Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medical-record-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

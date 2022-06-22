<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\medical_record\MedicalRecordLog */

$this->title = 'Create Medical Record Log';
$this->params['breadcrumbs'][] = ['label' => 'Medical Record Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-record-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\appointment\Appointment */

$this->title = 'Cập nhật lịch hẹn';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách lịch hẹn', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'cập nhật';
?>
<div class="appointment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

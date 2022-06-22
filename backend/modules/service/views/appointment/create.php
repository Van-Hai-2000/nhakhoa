<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\appointment\Appointment */

$this->title = 'Tạo lịch hẹn';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách lịch hẹn', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appointment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

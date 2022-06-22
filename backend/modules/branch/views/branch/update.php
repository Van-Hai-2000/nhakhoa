<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\branch\Branch */

$this->title = 'Cập nhật chi nhánh: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Chi nhánh', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="branch-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

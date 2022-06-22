<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\commission\Commission */

$this->title = 'Update Commission: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách hoa hồng', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="commission-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

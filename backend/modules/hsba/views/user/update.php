<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */

$this->title = 'Cập nhật bệnh nhân: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách bệnh nhân', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'cập nhật';
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

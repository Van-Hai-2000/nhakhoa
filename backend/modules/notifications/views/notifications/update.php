<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\notifications\Notifications */

$this->title = 'Cập nhật thông báo: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Thông báo', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="notifications-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

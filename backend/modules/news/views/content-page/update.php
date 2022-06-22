<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContentPage */

$this->title = 'Sửa trang: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý trang nội dung', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content-page-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

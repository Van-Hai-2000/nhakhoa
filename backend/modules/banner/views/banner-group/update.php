<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BannerGroup */

$this->title = 'Sửa nhóm banner: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhóm banner', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="banner-group-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

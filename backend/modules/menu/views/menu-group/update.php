<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuGroup */

$this->title = 'Cập nhật nhóm: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhóm menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="menu-group-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

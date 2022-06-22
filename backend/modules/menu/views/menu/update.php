<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = 'Cập nhật menu: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý menu', 'url' => ['menu-group/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="menu-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

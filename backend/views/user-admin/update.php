<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */

$this->title = 'Cập nhật tài khoản: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý tài khoản quản trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="user-admin-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'isNewRecord' => false
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */

$this->title = 'Tạo tài khoản';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý tài khoản quản trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'isNewRecord' => true
    ]) ?>

</div>

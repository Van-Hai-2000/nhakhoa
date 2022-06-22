<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = 'Cập nhật tài khoản: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý tài khoản', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="user-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>

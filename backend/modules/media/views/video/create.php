<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\media\Video */

$this->title = 'Tạo Video';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý video', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

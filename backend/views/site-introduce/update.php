<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SiteIntroduce */

$this->title = 'Giới thiệu website';
$this->params['breadcrumbs'][] = ['label' => 'Giới thiệu website'];
?>
<div class="site-introduce-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

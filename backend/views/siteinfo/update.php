<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Siteinfo */

$this->title = 'Cấu hình website';
$this->params['breadcrumbs'][] = ['label' => 'Cấu hình website'];
?>

<div class="siteinfo-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BannerGroup */

$this->title = 'Tạo nhóm banner';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhóm banner', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-group-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

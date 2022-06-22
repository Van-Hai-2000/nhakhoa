<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Banner */

$this->title = 'Tạo banner';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý banner', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

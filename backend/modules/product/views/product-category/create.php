<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\product\Product */

$this->title = 'Tạo nhóm';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhóm thủ thuật', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
    ]) ?>

</div>

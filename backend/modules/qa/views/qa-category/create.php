<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NewsCategory */

$this->title = 'Tạo danh mục';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý danh mục tin tức', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

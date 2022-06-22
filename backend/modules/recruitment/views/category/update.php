<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = 'Cập nhật ngành nghề: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý ngành nghề', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="category-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SiteIntroduce */

$this->title = 'Create Site Introduce';
$this->params['breadcrumbs'][] = ['label' => 'Site Introduces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-introduce-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sale\BranchSales */

$this->title = 'Create Branch Sales';
$this->params['breadcrumbs'][] = ['label' => 'Branch Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-sales-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

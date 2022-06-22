<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\sale\BranchSales */

$this->title = 'Update Branch Sales: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Branch Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="branch-sales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\review\CustomerReviews */

$this->title = 'Create Customer Reviews';
$this->params['breadcrumbs'][] = ['label' => 'Customer Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-reviews-create">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

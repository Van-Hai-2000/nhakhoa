<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sale\search\BranchSalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doanh số chi nhánh';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-sales-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'branch_id',
                'value' => 'branch.name'
            ],
            [
                'attribute' => 'money',
                'value' => function ($model) {
                    return number_format($model->money);
                }
            ],
            'type',
            // 'type_id',
            // 'week',
            // 'month',
            // 'year',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Yêu cầu xác nhận nạp V chuyển khoản';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'showFooter' => true,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'key',
                                'name',
                                'phone',
                                [
                                    'header' => 'Số tiền chuyển',
                                    'attribute' => 'order_total',
                                    'value' => function ($model) {
                                        return formatMoney($model->order_total) . ' = ' . $model->getTextV();
                                    },
                                    'footer' => \common\models\order\search\OrderSearch::getTotal($dataProvider->models, 'order_total'),
                                ],
                                [
                                    'label' => 'Ngày tạo',
                                    'attribute' => 'created_at',
                                    'value' => function ($model) {
                                        return date('H:i d/m/Y', $model->created_at);
                                    },
                                    'filter' => DateRangePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'created_at',
                                        'convertFormat' => true,
                                        'pluginOptions' => [
                                            'locale' => [
                                                'format' => 'd-m-Y'
                                            ],
                                        ],
                                    ]),
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update}'
                                ],
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
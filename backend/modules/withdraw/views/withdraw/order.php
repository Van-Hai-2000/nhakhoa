<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý đơn hàng';
$this->params['breadcrumbs'][] = $this->title;

$arr = [
    \common\components\payments\ClaPayment::PAYMENT_METHOD_CK => \Yii::t('app', 'payment_ck'),
    \common\components\payments\ClaPayment::PAYMENT_METHOD_VNPay => \Yii::t('app', 'vnp_method'),
];

?>
<div class="order-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Xuất exel', ['exel'], ['class' => 'btn btn-success pull-right']) ?>
                    <?php //  Html::a('Tạo đơn hàng', ['create'], ['class' => 'btn btn-success pull-right']) 
                    ?>
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
                                    'attribute' => 'order_total',
                                    'value' => function ($model) {
                                        return number_format($model->order_total, 0, ',', '.');
                                    },
                                    'footer' => \common\models\order\search\OrderSearch::getTotal($dataProvider->models, 'order_total'),
                                ],
                                [
                                    'attribute' => 'payment_status',
                                    'filter' => Html::activeDropDownList($searchModel, 'payment_status', common\models\order\Order::arrayPaymentStatus(), ['class' => 'form-control', 'prompt' => 'Chọn']),
                                    'value' => function ($model) {
                                        $transfer = Order::getPaymentStatusName($model->payment_status);
                                        return $transfer;
                                    }
                                ],
                                [
                                    'attribute' => 'payment_method',
                                    'format' => 'raw',
                                    'filter' => Html::activeDropDownList($searchModel, 'payment_method', $arr, ['class' => 'form-control', 'prompt' => 'Chọn']),
                                    'value' => function ($model) {
                                        return \common\components\payments\ClaPayment::getName($model['payment_method']);
                                    }
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
                                    'template' => '{update}{delete}'
                                ],
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
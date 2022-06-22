<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\voucher\Voucher;

/* @var $this yii\web\View */
/* @var $searchModel common\models\voucher\VoucherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mã giảm giá';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'voucher',
                            [
                                'attribute' => 'type',
                                 'value' => function ($model) {
                                    return Voucher::getType()[$model->type];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'type', Voucher::getType(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'type_value',
                                'value' => function ($model) {
                                    return number_format($model->type_value);
                                }
                            ],
                            [
                                'attribute' => 'day_start',
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->day_start);
                                }
                            ],
                            [
                                'attribute' => 'day_end',
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->day_end);
                                }
                            ],
                            [
                                'attribute' => 'money_start',
                                'value' => function ($model) {
                                    return number_format($model->money_start);
                                }
                            ],
                            [
                                'attribute' => 'money_end',
                                'value' => function ($model) {
                                    return number_format($model->money_end);
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    return Voucher::getStatus()[$model->status];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', Voucher::getStatus(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
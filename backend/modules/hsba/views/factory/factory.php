<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\medical_record\FactorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách đặt xưởng';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="factory-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'user_id',
                                'value' => 'user.username'
                            ],
                            [
                                'attribute' => 'device_id',
                                'value' => 'loaimau.name'
                            ],
                            'quantity',
                            [
                                'attribute' => 'money',
                                'label' => 'Đơn giá',
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name'
                            ],
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    return \common\models\medical_record\Factory::getStatus()[$model->status];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\medical_record\Factory::getStatus(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}{update}{delete}',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>


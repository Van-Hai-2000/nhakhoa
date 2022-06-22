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
                                'attribute' => 'medical_record_id',
                                'label' => 'Mã HSBA',
                                'content' => function ($model) {
                                    return '<a href="' . \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $model->medical_record_id]) . '">' . $model->medical_record_id . '</a>';
                                }
                            ],
                            [
                                'attribute' => 'factory_id',
                                'value' => 'userAdmin.fullname'
                            ],
                            [
                                'attribute' => 'device_id',
                                'value' => 'loaimau.name'
                            ],
                            [
                                'attribute' => 'money',
                                'label' => 'Đơn giá',
                                'options' => ['style' => 'width:100px'],
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'quantity',
                                'label' => 'S.lượng',
                                'options' => ['style' => 'width:50px']
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name'
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => 'Ngày gửi',
                                'options' => ['style' => 'width:100px'],
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->created_at);
                                }
                            ],
                            'insurance_code',
                            [
                                'attribute' => 'time_return',
                                'value' => function ($model) {
                                    return isset($model->time_return) && $model->time_return ? date('d-m-Y H:i:s', $model->time_return) : 'Chờ xưởng xác nhận';
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    return \common\models\medical_record\Factory::getStatus()[$model->status];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\medical_record\Factory::getStatus(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'options' => ['style' => 'width:70px'],
                                'template' => '{view}{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        if ($model->medical_record_id) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '/admin/user/medical-record/add?id=' . $model->medical_record_id, [
                                                'title' => Yii::t('app', 'Chỉnh sửa'),
                                            ]);
                                        }
                                        return '';
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>


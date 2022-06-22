<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý tài khoản quản trị';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo tài khoản', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'username',
                                'options' => ['style' => 'width:120px']
                            ],
                            [
                                'attribute' => 'fullname',
                                'options' => ['style' => 'width:150px']
                            ],
                            'email',
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name',
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'status',
                                'options' => ['style' => 'width:100px'],
                                'value' => function($model) {
                                    return $model->status ? 'Kích hoạt' : 'Dừng hoạt động';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', [\backend\models\UserAdmin::STATUS_DELETED => 'Dừng hoạt động', \backend\models\UserAdmin::STATUS_ACTIVE => 'Kích hoạt'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            'created_at' => [
                                'header' => 'Ngày tạo',
                                'content' => function($model) {
                                    return date('d/m/Y', $model->created_at);
                                }
                            ],
                            'type' => [
                                'header' => 'Loại tải khoản',
                                'content' => function($model) {
                                    return \backend\models\UserAdmin::getTypeName($model->vai_tro);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{authenticate}',
                                'buttons' => [
                                    'authenticate' => function ($url, $model) {
                                        return Html::a('Cấp quyền', 'auth?id='.$model->id, [
                                            'title' => Yii::t('app', 'Chỉnh sửa'),
                                            'class' => 'btn btn-success'
                                        ]);
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('Chỉnh sửa', 'update?id='.$model->id, [
                                            'title' => Yii::t('app', 'Chỉnh sửa'),
                                            'class' => 'btn btn-primary'
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

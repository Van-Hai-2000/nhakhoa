<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Province;
use common\models\media\VideoCategory;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\media\VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý video';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo video', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'avatar' => [
                                'header' => 'Ảnh đại diện',
                                'content' => function($model) {
                                    return '<img style="max-width: 100px; max-height: 100px;" src="' . common\components\ClaHost::getImageHost() . $model->avatar_path . $model->avatar_name . '" />';
                                }
                            ],
                            [
                                'attribute' => 'ishot',
                                'value' => function($model) {
                                    return $model->ishot ? 'Có' : 'Không';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'ishot', [1 => 'Video hot'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'homeslide',
                                'value' => function($model) {
                                    return $model->homeslide ? 'Có' : 'Không';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'homeslide', [1 => 'Home Slide'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'category_id',
                                'value' => 'category.name',
                                'filter' => Html::activeDropDownList($searchModel, 'category_id', ArrayHelper::map(VideoCategory::find()->asArray()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => '--- Chọn ---'])
                            ],
                            // [
                            //     'attribute' => 'province_id',
                            //     'value' => 'province.name',
                            //     'filter' => Html::activeDropDownList($searchModel, 'province_id', ArrayHelper::map(Province::find()->asArray()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => '--- Chọn ---'])
                            // ],
                            [
                                'attribute' => 'status',
                                'value' => function($model) {
                                    return $model->status ? 'Hiển thị' : 'Ẩn';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', [1 => 'Hiển thị', 0 => 'Ẩn'], ['class' => 'form-control', 'prompt' => '--- Chọn ---'])
                            ],
                            'created_at' => [
                                'header' => 'Ngày tạo',
                                'content' => function($model) {
                                    return date('d/m/Y', $model->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}'
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

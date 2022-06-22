<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\thuchi\ThuChiCategoryhiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh mục thu chi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="branch-index">
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
                            'name',
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    return $model->status == 1 ? 'Hiển thị' : 'Ẩn';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', [0 => 'Ẩn', 1 => 'Hiển thị'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

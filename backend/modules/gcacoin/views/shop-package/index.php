<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Quản lý gói thời hạn Doanh nghiệp';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-category-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo danh mục', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'name',
                                [
                                    'attribute' => 'time',
                                    'content' => function ($model) {
                                        return $model->time ? round($model->time / 24 / 60 / 60, 1) . ' Ngày' : '';
                                    }
                                ],
                                'coin',
                                [
                                    'attribute' => 'status',
                                    'content' => function ($model) {
                                        return $model['status'] ? 'Active' : 'Hidden';
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
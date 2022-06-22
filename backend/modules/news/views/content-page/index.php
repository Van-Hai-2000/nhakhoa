<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ContentPageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý trang nội dung';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-page-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo trang', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'title',
                            'short_description',
                            'created_at' => [
                                'header' => 'Ngày tạo',
                                'content' => function($model) {
                                    return date('d/m/Y', $model->created_at);
                                }
                            ],
                            'status' => [
                                'header' => 'Trạng thái',
                                'content' => function($model) {
                                    return $model->status ? 'Hiển thị' : 'Ẩn';
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

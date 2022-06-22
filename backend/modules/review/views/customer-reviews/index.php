<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer Reviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-reviews-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo đánh giá', ['create'], ['class' => 'btn btn-success  pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            'images' => [
                                'header' => 'Ảnh',
                                'content' => function($model) {
                                    return '<img src="' . \common\components\ClaHost::getImageHost() . $model['avatar_path'] . 's100_100/' . $model['avatar_name'] . '" />';
                                }
                            ],
                            'review',
                            'customer_name',
                            // 'created_time',
                            'id',
                            'score' => [
                                'header' => 'Điểm(5)',
                                'content' => function($model) {
                                    return $model['score'];
                                }
                            ],
                            // 'review_en',
                            // 'customer_name_en',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

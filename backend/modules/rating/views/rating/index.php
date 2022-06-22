<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\rating\Rating;

/* @var $this yii\web\View */
/* @var $searchModel common\models\rating\RatingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'rate');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
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

                            // 'id',
                            [
                                'attribute' => 'type',
                                'value' => function($model) {
                                    return Rating::getTypeNull($model->type);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'type',Rating::getType(), ['class' => 'form-control'])
                            ],
                            'object_id',
                            'name',
                            // 'address',
                            // 'email:email',
                            'rating',
                            
                            'content:ntext',
                            'created_at' => [
                                'header' => Yii::t('app', 'created_time'),
                                'content' => function($model) {
                                    return date('d/m/Y', $model->created_at);
                                }
                            ],
                            // 'status',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}'
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LoaiMauSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Loại mẫu';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="loai-mau-index">
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
                                'attribute' => 'money',
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    return $model->status ? 'Hiện' : 'Ẩn';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', [1 => 'Hiện', 0 => 'Ẩn'], ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                            ],
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>



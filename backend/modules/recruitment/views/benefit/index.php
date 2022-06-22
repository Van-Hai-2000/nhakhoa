<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\recruitment\search\BenefitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý phúc lợi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="benefit-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo phúc lợi', ['create'], ['class' => 'btn btn-success pull-right']) ?>
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
                            'icon_class',
                            'order',
                            'isinput' => [
                                'attribute' => 'isinput',
                                'filter' => Html::activeDropDownList($searchModel, 'isinput', [0 => 'Không', 1 => 'Có'], ['class' => 'form-control', 'prompt' => '--- Chọn ---']),
                                'value' => function($model) {
                            return $model->isinput ? 'Có' : 'Không';
                        }
                            ],
                            // 'status',
                            // 'created_at',
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

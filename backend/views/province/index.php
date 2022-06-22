<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProvinceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý tỉnh/thành phố';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="province-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?php // echo Html::a('Tạo tỉnh/thành phố', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            'name',
                            'type',
                            // 'latlng',
                            // 'avatar_path',
                            // 'avatar_name',
                            // 'position',
                            // 'show_in_home:boolean',
                            'region' => [
                                'header' => 'Miền',
                                'content' => function($model) {
                                    if ($model->region == 1) {
                                        return 'Miền bắc';
                                    } else if ($model->region == 2) {
                                        return 'Miền trung';
                                    } else if ($model->region == 3) {
                                        return 'Miền nam';
                                    }
                                }
                            ],
                            // 'ishot:boolean',
                            // 'order',
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

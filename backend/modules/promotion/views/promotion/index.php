<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $searchModel common\models\product\searchProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'promotion_management');
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .plus {
        display: inline-block;
        padding: 5px 30px;
        background: #26B99A;
        color: #fff;
        border-radius: 6px;
    }
</style>
<div class="product-index">
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            // 'id',
                            'name',
                            // 'showinhome:boolean',
                            'startdate' => [
                                'header' => Yii::t('app', 'time_start'),
                                'content' => function($model) {
                                    date_default_timezone_set("Asia/Bangkok");
                                    return date('d/m/Y H:i', $model->startdate);
                                }
                            ],
                            'enddate' => [
                                'header' => Yii::t('app', 'time_end'),
                                'content' => function($model) {
                                    date_default_timezone_set("Asia/Bangkok");
                                    return date('d/m/Y H:i', $model->enddate);
                                }
                            ],
                            'time_space',
                            // 'status',
                            [
                                'header' => 'Thêm sản phẩm',
                                'content' => function($model) {
                                    return '<a class="plus" href="'.Url::to(['/promotion/promotion/add-product-space', 'id' => $model->id]).'"><i class="fa fa-plus"></i></a>';
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}'
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>



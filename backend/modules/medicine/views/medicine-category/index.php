<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\product\ProductCategory;
use yii\helpers\ArrayHelper;
use common\models\product\Product;
use common\models\shop\Shop;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $searchModel common\models\product\searchProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý danh mục thuốc - thiết bị';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                'images' => [
                                    'header' => Yii::t('app', 'image'),
                                    'content' => function ($model) {
                                        return '<img src="' . ClaHost::getImageHost() . $model['avatar_path'] . 's100_100/' . $model['avatar_name'] . '" />';
                                    }
                                ],
                                'name',
                                [
                                    'attribute' => 'status',
                                    'content' => function ($model) {
                                        return $model->status ? 'Hiện' : 'Ẩn';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'status', [1 => 'Hiện', 0 => 'Ẩn'], ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                                ],
                                'order',
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
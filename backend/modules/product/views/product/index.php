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

$this->title = 'Quản lý thủ thuật';
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
                    <?php
                    $model_category = new ProductCategory();
                    ?>
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
                                'attribute' => 'category_id',
                                'content' => function ($model) {
                                    $cat = ProductCategory::findOne($model->category_id);
                                    return $cat ? $cat->name : '';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'category_id', $model_category->optionsCategory(), ['class' => 'form-control'])
                            ],
                            [
                                'attribute' => 'price_market',
                                'value' => function ($model) {
                                    return number_format($model->price_market, 0, ',', '.');
                                }
                            ],
                            [
                                'attribute' => 'price',
                                'value' => function ($model) {
                                    return number_format($model->price, 0, ',', '.');
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    return $model->status ? 'Hiện' : 'Ẩn';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', [1 => 'Hiện', 0 => 'Ẩn'], ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
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
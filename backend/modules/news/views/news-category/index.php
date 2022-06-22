<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use common\models\news\NewsCategory;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NewsCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý danh mục tin tức';
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
                    <?php
                    $model = new NewsCategory();
                    $provider = new ArrayDataProvider([
                        'allModels' => $model->getDataProvider(),
                        'sort' => [
                            'attributes' => ['id', 'name', 'parent'],
                        ],
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ]);
                    ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name' => [
                                'header' => 'Tên danh mục',
                                'content' => function($model) {
                                    return $model['name'];
                                }
                            ],
                            [
                                'header' => 'Hiện trang chủ',
                                'content' => function($model) {
                                    return $model['show_in_home'] ? 'Có' : 'Không';
                                }
                            ],
                            [
                                'header' => 'Hiện bên phải trang chủ',
                                'content' => function($model) {
                                    return $model['show_home_right'] ? 'Có' : 'Không';
                                }
                            ],
                            [
                                'header' => 'Săp xếp',
                                'content' => function($model) {
                                    return $model['order'];
                                }
                            ],
                            'status' => [
                                'header' => 'Trạng thái',
                                'content' => function($model) {
                                    return $model['status'] ? 'Hiển thị' : 'Ẩn';
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

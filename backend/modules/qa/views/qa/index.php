<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\qa\QACategory;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý câu hỏi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo câu hỏi', ['create'], ['class' => 'btn btn-success pull-right']) ?>
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
                            'category_id' => [
                                'header' => 'Danh mục',
                                'content' => function($model) {
                                    $category = QACategory::findOne($model->category_id);
                                    return isset($category->name) ? $category->name : '';
                                }
                            ],
                            'status' => [
                                'header' => 'Trạng thái',
                                'content' => function($model) {
                                    return $model->status ? 'Hiển thị' : 'Ẩn';
                                }
                            ],
                            'publicdate' => [
                                'header' => 'Ngày đăng lên web',
                                'content' => function($model) {
                                    return date('d/m/Y', $model->publicdate);
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

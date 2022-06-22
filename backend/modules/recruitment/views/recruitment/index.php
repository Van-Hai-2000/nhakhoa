<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\recruitment\Recruitment;
use common\models\recruitment\Category;
use common\models\Province;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\RecruitmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý tuyển dụng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recruitment-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Đăng tin tuyển dụng', ['create'], ['class' => 'btn btn-success pull-right']) ?>
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
                            'level' => [
                                'header' => 'Chức danh',
                                'content' => function($model) {
                                    $levels = Recruitment::arrayLevel();
                                    return $levels[$model->level] ? $levels[$model->level] : '';
                                }
                            ],
                            'category_id' => [
                                'header' => 'Nhóm ngành nghề',
                                'content' => function($model) {
                                    $array_category = Category::getNameCategory($model->category_id);
                                    return join(', ', $array_category);
                                }
                            ],
                            'locations' => [
                                'header' => 'Nơi làm việc',
                                'content' => function($model) {
                                    $array_location = Province::getNameProvince($model->locations);
                                    return join(', ', $array_location);
                                }
                            ],
                            'created_at' => [
                                'header' => 'Ngày tạo',
                                'content' => function($model) {
                                    return date('d/m/Y', $model->created_at);
                                }
                            ],
                            'status' => [
                                'header' => 'Trạng thái',
                                'content' => function($model) {
                                    $status = $model->status == 1 ? 'Hiển thị' : 'Ẩn';
                                    $class = $model->status == 1 ? 'btn-info' : 'btn-default';
                                    return '<button type="button" class="btn-status btn ' . $class . '">' . $status . '</button>';
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

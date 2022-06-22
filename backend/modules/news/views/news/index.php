<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\news\NewsCategory;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý tin tức';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    select {
        width: 100%;
        height: 32px;
        background: #fff;
        padding: 0px 10px;
        min-width: 97px;
    }
</style>
<div class="news-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Đăng tin', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'images' => [
                                    'header' => Yii::t('app', 'image'),
                                    'content' => function ($model) {
                                        return '<img src="' . ClaHost::getImageHost() . $model['avatar_path'] . 's100_100/' . $model['avatar_name'] . '" />';
                                    }
                                ],
                                'title',
                                'category_id' => [
                                    'header' => 'Danh mục',
                                    'content' => function ($model) {
                                        $category = NewsCategory::findOne($model->category_id);
                                        return isset($category->name) ? $category->name : '';
                                    }
                                ],
                                'ishot:boolean',
                                [
                                    'attribute' => 'status',
                                    'content' => function ($model) {
                                        $html = '<select onchange="changeStatusProduct(this, ' . $model->id . ')">';
                                        $html .= '<option value="0" ' . ($model->status == 0 ? 'selected' : '') . '>Ẩn bài</option>';
                                        $html .= '<option value="1" ' . ($model->status == 1 ? 'selected' : '') . '>Hiện thị</option>';
                                        $html .= '<option value="2" ' . ($model->status == 2 ? 'selected' : '') . '>Chờ duyệt</option>';
                                        $html .= '</select>';
                                        return $html;
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'status', [2 => Yii::t('app', 'status_2'), 1 => Yii::t('app', 'status_1'), 0 => Yii::t('app', 'status_0')], ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                                ],
                                'publicdate' => [
                                    'header' => 'Ngày đăng lên web',
                                    'content' => function ($model) {
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
<script type="text/javascript">
    function changeStatusProduct(_this, nid) {
        var status = $(_this).val();
        $.getJSON(
            '<?= \yii\helpers\Url::to(['/news/news/change-status']) ?>', {
                status: status,
                nid: nid
            },
            function(data) {
                if (data.code == 200) {
                    alert('Cập nhật trạng thái thành công');
                    location.reload();
                }
            }
        );
    }
</script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\banner\BannerGroup;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý banner';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo banner', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'group_id' => [
                                    'header' => 'Nhóm banner',
                                    'content' => function ($model) {
                                        $group = BannerGroup::findOne($model->group_id);
                                        return $group->name;
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'group_id', ['' => 'Tất cả'] + BannerGroup::optionsBannerGroup(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                                ],
                                'name',
                                'src' => [
                                    'header' => 'Banner',
                                    'content' => function ($model) {
                                        return '<img style="max-width: 100px; max-height: 100px;" src="' . $model->src . '" />';
                                    }
                                ],
                                'link',
                                'order',
                                'status' => [
                                    'header' => 'Trạng thái',
                                    'content' => function ($model) {
                                        return $model->status ? 'Hiển thị' : 'Ẩn';
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
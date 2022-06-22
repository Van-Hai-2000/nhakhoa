<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\shop\Shop;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nạp OCOP V';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
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
                            'username',
                            [
                                'header' => Yii::t('app', 'shop'),
                                'content' => function($model) {
                                    if($shop = Shop::findOne($model->id)) {
                                        return '<a href="'.Url::to(['/user/shop/index', 'ShopSearch[name]' => $shop->name]).'">'.$shop->name.'</a>';
                                    }
                                    return 'N/A';
                                }
                            ],
                            'phone',
                            'email:email',
                            'status' => [
                                'attribute' => 'status',
                                'value' => function($model) {
                                    return $model->status == \common\components\ClaLid::STATUS_ACTIVED ? 'Hoạt Động' : 'Đã khóa';
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                                'header' => 'Nạp tiền',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                                            'title' => Yii::t('app', 'lead-update'),
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

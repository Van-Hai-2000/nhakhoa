<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sản phẩm trong kho';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?php // Html::a('Tạo đơn hàng', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'order_id',
                            'image' => [
                                'header' => 'Ảnh',
                                'content' => function($model) {
                                    $product = \common\models\product\Product::findOne($model->product_id);
                                    return '<img src="' . common\components\ClaHost::getImageHost() . $product['avatar_path'] . 's100_100/' . $product['avatar_name'] . '" />';
                                }
                            ],
                            'product_id' => [
                                'header' => 'Sản phẩm',
                                'content' => function($model) {
                                    $product = \common\models\product\Product::findOne($model->product_id);
                                    return $product['name'];
                                }
                            ],
                            'code',
                            'color',
                            'size',
                            'quantity',
                            'price',
                            'weight',
                        // 'status',
//                            ['class' => 'yii\grid\ActionColumn', ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

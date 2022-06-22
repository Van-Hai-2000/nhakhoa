<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\product\Product;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sản phẩm chờ nhập';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?php echo Html::a('Nhập sản phẩm', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered"><thead>
                            <tr>
                                <th>#</th>
                                <th>Tên sản phẩm</th>
                                <th>Màu sắc</th>
                                <th>Size</th>
                                <th>Mã gốc</th>
                                <th>Mã sản phẩm</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($items as $code_detail => $item) {
                                $product = Product::findOne($item['product_id']);
                                ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= $product['name'] ?></td>
                                    <td><?= $item['color'] ?></td>
                                    <td><?= $item['size'] ?></td>
                                    <td><?= $item['code'] ?></td>
                                    <td><?= $code_detail ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

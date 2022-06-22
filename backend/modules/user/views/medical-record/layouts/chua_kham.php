<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 12/7/2021
 * Time: 9:26 AM
 */

use yii\grid\GridView;

?>
<?php if ($dataProvider->getTotalCount() > 0): ?>
    <div class="medical-record-view">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Chưa khám</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'product_category_id',
                                    'value' => 'productCategory.name'
                                ],
                                [
                                    'attribute' => 'product_id',
                                    'value' => 'product.name'
                                ],
                                [
                                    'attribute' => 'quantity',
                                    'label' => 'Số lần còn lại',
                                    'value' => function ($model) {
                                        return $model->quantity - $model->quantity_use;
                                    }
                                ],
                                [
                                    'attribute' => 'money',
                                    'value' => function ($model) {
                                        return number_format($model->money);
                                    }
                                ],
                                [
                                    'attribute' => 'money',
                                    'label' => 'Tổng tiền',
                                    'value' => function ($model) {
                                        return number_format($model->money * ($model->quantity - $model->quantity_use));
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
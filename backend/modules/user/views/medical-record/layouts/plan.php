<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 12/7/2021
 * Time: 9:53 AM
 */
use yii\grid\GridView;

?>
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
        'quantity',
        'quantity_use',
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
                return number_format($model->money * $model->quantity);
            }
        ],
    ],
]); ?>

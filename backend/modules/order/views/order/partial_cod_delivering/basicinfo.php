<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'showFooter' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'name',
//                            'email:email',
        'phone',
        'address',
        // 'district_id',
        // 'province_id',
        [
            'attribute' => 'order_total',
            'value' => function($model) {
                return number_format($model->order_total, 0, ',', '.');
            },
            'footer' => common\models\order\search\OrderSearch::getTotal($dataProvider->models, 'order_total'),
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'filter' => Html::activeDropDownList($searchModel, 'status', common\models\order\Order ::arrayStatus(), ['class' => 'form-control', 'prompt' => 'Chọn']),
            'value' => function($model) {
        $check = Order::checkOutOfStock($model->id);
        $status = Order::getNameStatus($model->status);
        if (!$check) {
            if ($model->status == 0) {
                // Hủy đơn hàng
                return '<span style="color: purple">' . $status . '</span>';
            } else if ($model->status == Order::ORDER_SUCCESS) {
                // Hoàn thành
                return '<span style="color: blue">' . $status . '</span>';
            } else if ($model->status == Order::ORDER_PROCESSING) {
                // Đang xử lý
                return '<span style="color: #fa8700">' . $status . '</span>';
            } else if ($model->status == Order::ORDER_WAITING_IMPORT_PRODUCT) {
                // Đang xử lý
                return '<span style="color: green">' . $status . '</span>';
            } else if ($model->status == Order::ORDER_DELIVERING || $model->status == Order::ORDER_COD_DELIVERING) {
                // Đang xử lý
                return '<span style="color: #1eda10">' . $status . '</span>';
            } else {
                return Order::getNameStatus($model->status);
            }
        } else {
            return '<span style="color: red">' . $status . '</span>';
        }
    }
        ],
        // 'note',
        [
            'label' => 'Thời gian tạo',
            'attribute' => 'created_at',
            'value' => function($model) {
                return date('d/m/Y', $model->created_at);
            },
//                                'format' => 'datetime',
            'filter' => DateRangePicker::widget([
                'model' => $searchModel,
                'attribute' => 'created_at',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => [
                        'format' => 'd-m-Y'
                    ],
                ],
            ]),
        ],
        [
            'label' => 'Thời gian cập nhật',
            'attribute' => 'updated_at',
            'value' => function($model) {
                return date('d/m/Y', $model->updated_at);
            },
        ],
        [
            'attribute' => 'user_delivery',
            'format' => 'raw',
            'filter' => Html::activeDropDownList($searchModel, 'user_delivery', \backend\models\UserAdmin ::optionsUserDelivery(), ['class' => 'form-control', 'prompt' => 'Chọn']),
            'value' => function($model) {
        return \backend\models\UserAdmin::generateSelectUserDelivery($model->user_delivery);
    }
        ],
        [
            'attribute' => 'received_money',
            'format' => 'raw',
            'value' => function($model) {
                return \backend\models\UserAdmin::generateSelectReceivedMoney($model->received_money);
            }
        ],
        // 'updated_at',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}'
        ],
    ],
]);
?>
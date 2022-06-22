<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Đơn hàng đang giao';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?php //  Html::a('Tạo đơn hàng', ['create'], ['class' => 'btn btn-success pull-right'])   ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
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
                            'phone' => [
                                'attribute' => 'phone',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return '<a href="tel:' . $model->phone . '">' . $model->phone . '</a>';
                                }
                            ],
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
                                } else if ($model->status == Order::ORDER_DELIVERY_SUCCESS) {
                                    return '<span style="color: blue">' . $status . '</span>';
                                } else {
                                    return Order::getNameStatus($model->status);
                                }
                            } else {
                                return '<span style="color: red">' . $status . '</span>';
                            }
                        }
                            ],
                            // 'note',
                            'updated_at' => [
                                'header' => 'Thời gian tạo',
                                'content' => function($model) {
                                    return date('H:i d/m/Y', $model->updated_at);
                                }
                            ],
                            [
                                'header' => 'Trạng thái đơn hàng',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return \backend\models\UserAdmin::generateButtonDelivery($model->status);
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
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('.delivery-success').click(function () {
            var order_id = $(this).closest('tr').attr('data-key');
            $.getJSON(
                    '<?= Url::to(['/order/order/update-delivery-success']) ?>',
                    {order_id: order_id},
                    function (data) {
                        if (data.code == 200) {
                            alert('Cập nhật thành công.');
                            location.reload();
                        }
                    }
            );
        });

        $('.delivery-waiting').click(function () {
            var order_id = $(this).closest('tr').attr('data-key');
            $.getJSON(
                    '<?= Url::to(['/order/order/update-delivery-waiting']) ?>',
                    {order_id: order_id},
                    function (data) {
                        if (data.code == 200) {
                            alert('Cập nhật thành công.');
                            location.reload();
                        }
                    }
            );
        });

        $('.received_money').click(function () {
            var received_money = 0;
            var order_id = $(this).closest('tr').attr('data-key');
            if ($(this).is(':checked')) {
                received_money = 1;
            }
            $.getJSON(
                    '<?= Url::to(['/order/order/update-received-money']) ?>',
                    {received_money: received_money, order_id: order_id},
                    function (data) {
                        if (data.code == 200) {
                            alert('Cập nhật thành công.');
                            location.reload();
                        }
                    }
            );
        });

        $('.select_user_delivery').change(function () {
            var user_id = $(this).val();
            var order_id = $(this).closest('tr').attr('data-key');
            if (user_id != 0) {
                $.getJSON(
                        '<?= Url::to(['/order/order/update-user-delivery']) ?>',
                        {user_id: user_id, order_id: order_id},
                        function (data) {
                            if (data.code == 200) {
                                alert('Cập nhật thành công.');
                                location.reload();
                            }
                        }
                );
            }
        });
    });
</script>

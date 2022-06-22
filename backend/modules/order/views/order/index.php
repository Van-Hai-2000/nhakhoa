<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý đơn hàng';
$this->params['breadcrumbs'][] = $this->title;

$arr = [
    \common\components\payments\ClaPayment::PAYMENT_METHOD_NR => \Yii::t('app', 'cash_method'),
    \common\components\payments\ClaPayment::PAYMENT_METHOD_QR => \Yii::t('app', 'qr_method'),
    // self::PAYMENT_METHOD_MEMBER => Yii::t('app', 'member_method'),
    \common\components\payments\ClaPayment::PAYMENT_METHOD_MEMBERIN => \Yii::t('app', 'member_method'),
    \common\components\payments\ClaPayment::PAYMENT_METHOD_VNPay => \Yii::t('app', 'vnp_method'),
];

?>
<div class="order-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?= Html::encode($this->title) ?></h2>
                        <?= Html::a('Xuất exel', ['exel'], ['class' => 'btn btn-success pull-right']) ?>
                        <?php //  Html::a('Tạo đơn hàng', ['create'], ['class' => 'btn btn-success pull-right']) 
                        ?>
                        <div class="clearfix"></div>
                        <div class="toll2">
                            <div class="col-md-3 col-sm-5 col-xs-8">
                                Nhập số bản ghi: <input type="text" name="limit" value="<?= isset($_GET['limit']) ? $_GET['limit'] : '' ?>">
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-4">
                                <button class="btn btn-success">Chọn</button>
                                <span class="btn btn-primary" onclick="exceller()">Export</button>
                            </div>
                            <script>
                                function exceller() {
                                    var uri = 'data:application/vnd.ms-excel;base64,',
                                        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
                                        base64 = function(s) {
                                            return window.btoa(unescape(encodeURIComponent(s)))
                                        },
                                        format = function(s, c) {
                                            return s.replace(/{(\w+)}/g, function(m, p) {
                                                return c[p];
                                            })
                                        }
                                    var toExcel = $('.table-striped.table-bordered').first().html();
                                    var ctx = {
                                        worksheet: name || '',
                                        table: toExcel
                                    };
                                    var link = document.createElement("a");
                                    link.download = "export.xls";
                                    link.href = uri + base64(format(template, ctx))
                                    link.click();
                                }
                            </script>
                        </div>
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
                                'phone',
                                'address',
                                // 'district_id',
                                'shipfee',
                                [
                                    'attribute' => 'order_total',
                                    'value' => function ($model) {
                                        return number_format($model->order_total, 0, ',', '.');
                                    },
                                    'footer' => \common\models\order\search\OrderSearch::getTotal($dataProvider->models, 'order_total'),
                                ],
                                [
                                    'attribute' => 'payment_status',
                                    'filter' => Html::activeDropDownList($searchModel, 'payment_status', common\models\order\Order::arrayPaymentStatus(), ['class' => 'form-control', 'prompt' => 'Chọn']),
                                    'value' => function ($model) {
                                        $transfer = Order::getPaymentStatusName($model->payment_status);
                                        return $transfer;
                                    }
                                ],
                                [
                                    'attribute' => 'payment_method',
                                    'format' => 'raw',
                                    'filter' => Html::activeDropDownList($searchModel, 'payment_method', $arr, ['class' => 'form-control', 'prompt' => 'Chọn']),
                                    'value' => function ($model) {
                                        return \common\components\payments\ClaPayment::getName($model['payment_method']);
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'filter' => Html::activeDropDownList($searchModel, 'status', common\models\order\Order::arrayStatus(), ['class' => 'form-control', 'prompt' => 'Chọn']),
                                    'value' => function ($model) {
                                        $check = Order::checkOutOfStock($model->id);
                                        $status = Order::getNameStatus($model->status);
                                        return Order::getNameStatus($model->status);
                                    }
                                ],
                                // 'note',
                                //                            'created_at' => [
                                //                                'header' => 'Thời gian tạo',
                                //                                'content' => function($model) {
                                //                                    return date('d/m/Y', $model->created_at);
                                //                                }
                                //                            ],
                                [
                                    'label' => 'Ngày tạo',
                                    'attribute' => 'created_at',
                                    'value' => function ($model) {
                                        return date('H:i d/m/Y', $model->created_at);
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
                                // 'updated_at',
                                [
                                    'label' => 'Note',
                                    'header' => 'Note',
                                    'content' => function ($model) {
                                        $note = Order::getLatestOrderNote($model->id);
                                        return isset($note['note']) ? $note['note'] : '';
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update}{delete}'
                                ],
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
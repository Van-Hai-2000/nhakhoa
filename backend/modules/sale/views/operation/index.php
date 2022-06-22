<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sale\search\OperationSalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Thống kê thủ thuật';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-sales-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                    <div class="form-search-time">
                        <input type="date" name="start" id="time_start"
                               value="<?= isset($params['OperationSalesSearch']['time_start']) && $params['OperationSalesSearch']['time_start'] ? date('Y-m-d', $params['OperationSalesSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['OperationSalesSearch']['time_end']) && $params['OperationSalesSearch']['time_end'] ? date('Y-m-d', $params['OperationSalesSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>
                        <a href="<?= $url ?>" class="btn btn-primary pull-right" style="margin-left: 20px">Xuất Excel</a>
                        <h2 class="pull-right" style="font-weight: bold;font-size: 20px">Tổng tiền: <?= number_format(\common\components\ClaNhakhoa::getTotal($dataProvider->models,'money')) ?></h2>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'product_category_id',
                                'value' => 'productCategory.name',
                            ],
                            [
                                'attribute' => 'product_id',
                                'value' => 'product.name',
                            ],
                            [
                                'attribute' => 'doctor_id',
                                'value' => 'userAdmin.fullname',
                                'filter' => Html::activeDropDownList($searchModel, 'doctor_id', \backend\models\UserAdmin::getDoctor(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name',
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'money',
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->created_at);
                                },
                            ],
//                            [
//                                'attribute' => 'type_time',
//                                'value' => function ($model) {
//                                    return date('d-m-Y', $model->created_at);
//                                },
//                                'filter' => Html::activeDropDownList($searchModel, 'type_time', [1 => 'Hôm nay',2 => 'Tuần này', 3 => 'Tháng này'], ['class' => 'form-control', 'prompt' => 'Chọn thời gian'])
//                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#search').click(function () {
        $.ajax({
            type: 'GET',
            cache: false,
            url: '<?= \yii\helpers\Url::to(['set-url']) ?>',
            data: {
                time_start: $('#time_start').val(),
                time_end: $('#time_end').val(),
                params: '<?= json_encode($params) ?>'
            },
            success: function (data) {
                window.location.href = data;
            },
        });
    })
</script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sale\search\DoctorSalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doanh số bác sĩ';
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
                               value="<?= isset($params['DoctorSalesSearch']['time_start']) && $params['DoctorSalesSearch']['time_start'] ? date('Y-m-d', $params['DoctorSalesSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['DoctorSalesSearch']['time_end']) && $params['DoctorSalesSearch']['time_end'] ? date('Y-m-d', $params['DoctorSalesSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>

                        <h2 class="pull-right" style="font-weight: bold;font-size: 20px;margin-right: 15px">Tổng
                            tiền: <?= number_format(\common\components\ClaNhakhoa::getTotal($dataProvider->query->all(), 'money')) ?></h2>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'doctor_id',
                                'value' => 'userAdmin.fullname',
                            ],
                            [
                                'attribute' => 'money',
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'product_id',
                                'value' => 'product.name',
                            ],
                            [
                                'attribute' => 'medical_record_id',
                                'label' => 'Mã HSBA',
                                'content' => function ($model) {
                                    if ($model->medical_record_id) {
                                        return '<a target="_blank" href="' . \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $model->medical_record_id]) . '">' . $model->medical_record_id . '</a>';

                                    }
                                    return '';
                                }
                            ],
                            [
                                'attribute' => 'type_time',
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->created_at);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'type_time', [1 => 'Hôm nay',2 => 'Tuần này', 3 => 'Tháng này'], ['class' => 'form-control', 'prompt' => 'Chọn thời gian'])
                            ],
                            // 'medical_record_id',
                            // 'week',
                            // 'month',
                            // 'year',
                            // 'created_at',
                            // 'updated_at',

//                            ['class' => 'yii\grid\ActionColumn'],
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
            }
        });
    })
</script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sale\search\MedicalRecordItemMedicineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Thống kê thuốc';
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
                               value="<?= isset($params['MedicalRecordItemMedicineSearch']['time_start']) && $params['MedicalRecordItemMedicineSearch']['time_start'] ? date('Y-m-d', $params['MedicalRecordItemMedicineSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['MedicalRecordItemMedicineSearch']['time_end']) && $params['MedicalRecordItemMedicineSearch']['time_end'] ? date('Y-m-d', $params['MedicalRecordItemMedicineSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>
                        <h2 class="pull-right" style="font-weight: bold;font-size: 20px">Tổng
                            tiền: <?= number_format(\common\components\ClaNhakhoa::getTotalByQuantity($dataProvider->models, 'money')) ?></h2>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'branh_id',
                                'value' => 'branch.name',
                                'filter' => Html::activeDropDownList($searchModel, 'branh_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'medical_record_id',
                                'options' => ['style' => 'width:80px'],
                                'content' => function ($model) {
                                    if ($model->medical_record_id) {
                                        return '<a target="_blank" href="' . \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $model->medical_record_id]) . '">' . $model->medical_record_id . '</a>';

                                    }
                                    return '';
                                }
                            ],
                            [
                                'attribute' => 'user_id',
                                'value' => 'user.username',
                            ],
                            [
                                'attribute' => 'doctor_id',
                                'value' => 'userAdmin.fullname',
                            ],
                            [
                                'attribute' => 'medicine_id',
                                'value' => 'medicine.name',
                            ],
                            [
                                'attribute' => 'money',
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'quantity',
                                'options' => ['style' => 'width:80px'],
                            ],
                            [
                                'label' => 'Thành tiền',
                                'options' => ['style' => 'width:120px'],
                                'value' => function ($model) {
                                    return number_format($model->money * $model->quantity);
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d-m-Y H:i:s', $model->created_at);
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('Chỉnh sửa', '/admin/user/medical-record/add?id=' . $model->medical_record_id, [
                                            'title' => Yii::t('app', 'Chỉnh sửa'),
                                            'class' => 'btn btn-primary'
                                        ]);
                                    },
                                ],
                            ],
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
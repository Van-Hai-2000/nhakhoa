<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\medical_record\MedicalRecordLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lịch sử thay đổi bệnh án';
$this->params['breadcrumbs'][] = ['label' => 'Hồ sơ bệnh án', 'url' => ['/user/medical-record/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                    <div class="form-search-time">
                        <input type="date" name="start" id="time_start"
                               value="<?= isset($params['MedicalRecordLogSearch']['time_start']) && $params['MedicalRecordLogSearch']['time_start'] ? date('Y-m-d', $params['MedicalRecordLogSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['MedicalRecordLogSearch']['time_end']) && $params['MedicalRecordLogSearch']['time_end'] ? date('Y-m-d', $params['MedicalRecordLogSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'medical_record_id',
                            [
                                'attribute' => 'user_id',
                                'value' => 'userAdmin.fullname'
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name',
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', ['' => 'Tất cả'] + \common\models\branch\Branch::getBranch(), ['class' => 'form-control'])
                            ],
                            [
                                'attribute' => 'model',
                                'value' => function ($model) {
                                    return isset(\common\components\ClaLog::getTableName()[$model->model]) && \common\components\ClaLog::getTableName()[$model->model] ? \common\components\ClaLog::getTableName()[$model->model] : '';
                                }
                            ],
                            'action',
                            [
                                'attribute' => 'record_before',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $content = \common\components\ClaNhakhoa::getContentLog($model->record_before);
                                    return $content;
                                }
                            ],
                            [
                                'attribute' => 'record_after',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $content = \common\components\ClaNhakhoa::getContentLog($model->record_after);
                                    return $content;
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d-m-Y H:i:s', $model->created_at);
                                }
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
            }
        });
    })
</script>

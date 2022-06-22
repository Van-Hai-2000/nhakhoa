<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sale\search\MedicalRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Thống kê doanh số từ các nguồn giới thiệu';
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
                        <select style="border: 1px solid #ccc;padding: 7px 12px" name="introduce" id="introduce">
                            <option value="">Chọn nguồn giới thiệu</option>
                            <?php foreach (\backend\models\UserAdmin::getIntroduce() as $key => $value): ?>
                                <option value="<?= $key ?>" <?= isset($params['MedicalRecordSearch']['introduce']) && $params['MedicalRecordSearch']['introduce'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="date" name="start" id="time_start"
                               value="<?= isset($params['MedicalRecordSearch']['time_start']) && $params['MedicalRecordSearch']['time_start'] ? date('Y-m-d', $params['MedicalRecordSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['MedicalRecordSearch']['time_end']) && $params['MedicalRecordSearch']['time_end'] ? date('Y-m-d', $params['MedicalRecordSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>

                        <h2 class="pull-right" style="font-weight: bold;font-size: 20px">Tổng
                            tiền: <?= number_format(\common\components\ClaNhakhoa::getTotal($dataProvider->models, 'total_money')) ?></h2>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'introduce',
                                'filter' => false,
                                'value' => function ($model) {
                                    return isset(\backend\models\UserAdmin::getIntroduce()[$model->introduce]) && \backend\models\UserAdmin::getIntroduce()[$model->introduce] ? \backend\models\UserAdmin::getIntroduce()[$model->introduce] : '';
                                }
                            ],
                            [
                                'attribute' => 'introduce_id',
                                'value' => function ($model) {
                                    $user_admin = \backend\models\UserAdmin::findOne($model->introduce_id);
                                    return isset($user_admin) && $user_admin ? $user_admin->fullname ? $user_admin->fullname : $user_admin->username : '';
                                }
                            ],
                            [
                                'attribute' => 'total_money',
                                'value' => function ($model) {
                                    return number_format($model->total_money);
                                }
                            ],
                            [
                                'attribute' => 'id',
                                'content' => function ($model) {
                                    return '<a href="' . \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $model->id]) . '">' . $model->id . '</a>';
                                }
                            ],
                            [
                                'attribute' => 'branch_related',
                                'label' => 'Chi nhánh liên quan',
                                'value' => function ($model) {
                                    $branch = [];
                                    $payment = \common\models\user\PaymentHistory::find()->select('branch_id')->where(['medical_record_id' => $model->id])->distinct()->asArray()->all();
                                    if($payment){
                                        $branch_ids = array_column($payment,'branch_id','branch_id');
                                        $branch = \common\models\branch\Branch::find()->where(['id' => $branch_ids])->asArray()->all();
                                        $branch = array_column($branch,'name','id');
                                    }
                                    return implode(',', $branch);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'branch_related', \common\models\branch\Branch::getBranch(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->created_at);
                                },
                            ],

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
                introduce: $('#introduce').val(),
                params: '<?= json_encode($params) ?>'
            },
            success: function (data) {
                window.location.href = data;
            },
        });
    })
</script>
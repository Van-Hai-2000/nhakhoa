<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\thuchi\MedicalRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý công nợ';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="branch-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                    <div class="form-search-time">
                        <input type="date" name="start" id="time_start"
                               value="<?= isset($params['MedicalRecordSearch']['time_start']) && $params['MedicalRecordSearch']['time_start'] ? date('Y-m-d', $params['MedicalRecordSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['MedicalRecordSearch']['time_end']) && $params['MedicalRecordSearch']['time_end'] ? date('Y-m-d', $params['MedicalRecordSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>

                        <h2 class="pull-right" style="font-weight: bold;font-size: 20px;margin-right: 15px">Tổng
                            tiền: <?= number_format(\common\components\ClaNhakhoa::getTotalNo($dataProvider->models, 'money', 'total_money')) ?></h2>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'id',
                                'content' => function ($model) {
                                    return '<a style="color:green" target="_blank" href="'.\yii\helpers\Url::to(['/user/medical-record/add','id' => $model->id]).'">'.$model->id.'</a>';
                                }
                            ],
                            'username',
                            'phone',
                            [
                                'attribute' => 'created_at',
                                'label' => 'Tổng nợ',
                                'value' => function ($model) {
                                    return number_format($model->total_money - $model->money);
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
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \yii\helpers\Url::to(['/user/medical-record/add','id' => $model->id]), [
                                            'title' => Yii::t('app', 'Xem chi tiết'),
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
            url: '<?= \yii\helpers\Url::to(['set-url-cn']) ?>',
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
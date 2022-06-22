<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\user\search\MedicalRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hồ sơ bệnh án';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-record-index">
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

                        <?php if (\backend\modules\auth\components\Helper::checkRoute('/user/medical-record/log')) { ?>
                            <?= Html::a('Xem lịch sử', ['/user/medical-record-log/index'], ['class' => 'btn btn-warning pull-right']) ?>
                        <?php } ?>

                        <?php if (\backend\modules\auth\components\Helper::checkRoute('/user/medical-record/create')) { ?>
                            <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'status_color',
                                'label' => '',
                                'content' => function ($model) {
                                    return '<div class="box-checkbox"  style="text-align: center;font-size: 15px">
                                               <i class="fa fa-circle" style="color: ' . \common\models\user\MedicalRecord::getColor($model->status) . '"></i>
                                            </div>';
                                },
                            ],
                            [
                                'attribute' => 'id',
                                'options' => ['style' => 'width:80px']
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name',
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', ['' => 'Tất cả'] + \common\models\branch\Branch::getBranch(), ['class' => 'form-control'])
                            ],
                            'username',
                            'phone',
                            'name',
                            [
                                'attribute' => 'total_money',
                                'label' => 'Tổng T.Toán',
                                'value' => function ($model) {
                                    return number_format($model->total_money);
                                }
                            ],
                            [
                                'attribute' => 'money',
                                'label' => 'Tổng đã T.Toán',
                                'value' => function ($model) {
                                    return number_format($model->money);
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'value' => function ($modle) {
                                    return \common\models\user\MedicalRecord::getStatus()[$modle->status];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', ['' => 'Chọn'] + \common\models\user\MedicalRecord::getStatus(), ['class' => 'form-control'])
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => 'T.gian tạo',
                                'options' => ['style' => 'width:110px'],
                                'value' => function ($model) {
                                    return date('d-m-Y', $model->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit_docter}{update}{delete}{information}{log}',
                                'buttons' => [
                                    'edit_docter' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-plus"></span>', 'add?id=' . $model->id, [
                                            'title' => Yii::t('app', 'Khám bệnh - Điều trị'),
                                        ]);
                                    },
                                    'information' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-paperclip"></span>', 'information?id=' . $model->id, [
                                            'title' => Yii::t('app', 'Thông tin khác'),
                                        ]);
                                    },
                                    'log' => function ($url, $model) {
                                        if (\backend\modules\auth\components\Helper::checkRoute('/user/medical-record/log')) {
                                            return ' <a href="#" data-toggle="modal"
                                    data-target=".log" onclick="load_log(' . $model->id . ')"><i class="glyphicon glyphicon-film"></i></a>';
                                        }
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
<!--Lịch sử log-->
<?= $this->render('layouts/log/log'); ?>
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
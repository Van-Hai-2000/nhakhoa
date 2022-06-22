<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WaitingListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách bệnh nhân chờ khám';
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
                               value="<?= isset($params['WaitingListSearch']['time_start']) && $params['WaitingListSearch']['time_start'] ? date('Y-m-d', $params['WaitingListSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['WaitingListSearch']['time_end']) && $params['WaitingListSearch']['time_end'] ? date('Y-m-d', $params['WaitingListSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>
                        <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    </div>
                    <div class="pull-left" style="margin-top: 15px">
                        <h2 class="pull-left" style="font-size: 18px;margin-right: 15px;color: orange">Số người chờ
                            khám: <?= \common\models\WaitingList::getCount($dataProvider->models, 0) ?></h2>
                        <div class="pull-left" style="margin-right: 15px"> |</div>
                        <h2 class="pull-left" style="font-size: 18px;margin-right: 15px;color: green">Số người đang
                            khám: <?= \common\models\WaitingList::getCount($dataProvider->models, 1) ?></h2>
                        <div class="pull-left" style="margin-right: 15px"> |</div>
                        <h2 class="pull-left" style="font-size: 18px;margin-right: 15px;color: blue">Số người đã
                            khám xong: <?= \common\models\WaitingList::getCount($dataProvider->models, 2) ?></h2>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'stt',
                                'label' => 'STT',
                                'options' => ['style' => 'width:50px'],
                            ],
                            [
                                'attribute' => 'user_id',
                                'value' => 'user.username'
                            ],
                            [
                                'attribute' => 'medical_record_id',
                                'label' => 'Mã HSBA',
                                'options' => ['style' => 'width:80px'],
                                'content' => function ($model) {
                                    return '<a href="' . \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $model->medical_record_id]) . '">' . $model->medical_record_id . '</a>';
                                }
                            ],
                            [
                                'attribute' => 'doctor_id',
                                'value' => 'userAdmin.fullname'
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => function ($model) {
                                    return $model->branch->name;
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control'])
                            ],
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    return '<span style="color: ' . \common\models\WaitingList::getColor($model->status) . '">' . \common\models\WaitingList::getStatus()[$model->status] . '</span>';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\WaitingList::getStatus(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => 'T.gian tạo',
                                'options' => ['style' => 'width:150px'],
                                'value' => function ($model) {
                                    return date('d-m-Y H:i:s', $model->created_at);
                                }
                            ],

                            [
                                'attribute' => 'updated_at',
                                'label' => 'T.gian khám',
                                'options' => ['style' => 'width:150px'],
                                'value' => function ($model) {
                                    return \common\models\WaitingList::getTime($model->status, $model->updated_at);
                                }
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}{delete}',
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

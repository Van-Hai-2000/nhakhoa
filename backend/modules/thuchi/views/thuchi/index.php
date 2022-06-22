<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\thuchi\ThuChiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách thu, chi';
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
                                   value="<?= isset($params['ThuChiSearch']['time_start']) && $params['ThuChiSearch']['time_start'] ? date('Y-m-d', $params['ThuChiSearch']['time_start']) : date('Y-m-d', time()) ?>">
                            <span>&nbsp; đến ngày &nbsp;</span>
                            <input type="date" name="end" id="time_end"
                                   value="<?= isset($params['ThuChiSearch']['time_end']) && $params['ThuChiSearch']['time_end'] ? date('Y-m-d', $params['ThuChiSearch']['time_end']) : date('Y-m-d', time()) ?>">
                            <button class="btn btn-success" id="search">Tìm kiếm</button>
                            <?php if (\backend\modules\auth\components\Helper::checkRoute('/thuchi/thuchi/log')) { ?>
                                <?= Html::a('Xem lịch sử', ['/thuchi/thuchi-log/index'], ['class' => 'btn btn-warning pull-right', 'target' => '_blank']) ?>
                            <?php } ?>
                            <?php if (\backend\modules\auth\components\Helper::checkRoute('/thuchi/thuchi/in')) { ?>
                                <a href="javascript:void(0)" class="btn btn-primary pull-right"
                                   onclick="printDiv(this)">In</a>
                            <?php } ?>

                            <?php if (\backend\modules\auth\components\Helper::checkRoute('/thuchi/thuchi/create')) { ?>
                                <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                            <?php } ?>

                            <h2 class="pull-right" style="font-weight: bold;font-size: 20px;margin-right: 15px">Tổng
                                tiền: <?= number_format(\common\components\ClaNhakhoa::getTotal($dataProvider->query->all(), 'money')) ?></h2>
                        </div>
                    </div>
                    <div class="x_content">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                [
                                    'attribute' => 'id',
                                    'options' => ['style' => 'width:80px']
                                ],
                                'name',
                                [
                                    'attribute' => 'type',
                                    'value' => function ($model) {
                                        return \common\models\thuchi\ThuChi::getType()[$model->type];
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'type', \common\models\thuchi\ThuChi::getType(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                                ],
                                [
                                    'attribute' => 'type_payment',
                                    'label' => 'Hình thức T.Toán',
                                    'value' => function ($model) {
                                        return isset($model->type_payment) && $model->type_payment ? \common\models\user\PaymentHistory::getTypePayment()[$model->type_payment] : 'Tiền mặt';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'type_payment', \common\models\user\PaymentHistory::getTypePayment(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                                ],
                                [
                                    'attribute' => 'money',
                                    'value' => function ($model) {
                                        return number_format($model->money);
                                    }
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'value' => 'user.username',
                                ],
                                [
                                    'attribute' => 'branch_id',
                                    'value' => 'branch.name',
                                    'filter' => Html::activeDropDownList($searchModel, 'branch_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
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
                                    'attribute' => 'time',
                                    'value' => function ($model) {
                                        return date('d-m-Y H:i:s', $model->time);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}{update}{delete}',
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            if ($model->medical_record_id) {
                                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '/admin/user/medical-record/add?id=' . $model->medical_record_id, [
                                                    'title' => Yii::t('app', 'Xem chi tiết'),
                                                ]);
                                            }
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'update?id=' . $model->id, [
                                                'title' => Yii::t('app', 'Xem chi tiết'),
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            if ($model->medical_record_id) {
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/admin/user/medical-record/add?id=' . $model->medical_record_id, [
                                                    'title' => Yii::t('app', 'Xóa'),
                                                ]);
                                            }
                                            return '<a href="/admin/thuchi/thuchi/delete?id=' . $model->id . '" title="Xóa" aria-label="Xóa" data-pjax="0" data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>';
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
    <div id="print_content" style="display: none">

        <?= \common\widgets\print_thu_chi\PrintThuChi::widget([
            'view' => 'view',
            'data' => [
                'data' => $dataProvider->models,
                'time_start' => isset($params['ThuChiSearch']['time_start']) && $params['ThuChiSearch']['time_start'] ? date('Y-m-d', $params['ThuChiSearch']['time_start']) : date('Y-m-d', time()),
                'time_end' => isset($params['ThuChiSearch']['time_end']) && $params['ThuChiSearch']['time_end'] ? date('Y-m-d', $params['ThuChiSearch']['time_end']) : date('Y-m-d', time()),
                'branch_id' => isset($params['ThuChiSearch']['branch_id']) && $params['ThuChiSearch']['branch_id'] ? $params['ThuChiSearch']['branch_id'] : '',
            ]
        ]) ?>
    </div>
    <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
    <script>
        function printDiv(t) {
            window.frames["print_frame"].document.body.innerHTML = document.getElementById('print_content').innerHTML;
            window.frames["print_frame"].window.focus();
            window.frames["print_frame"].window.print();
        }


        function printDiv1(t) {
            var printContents = document.getElementById('print_content').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

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

    <!--Lịch sử log-->
<?= $this->render('layouts/log/log'); ?>
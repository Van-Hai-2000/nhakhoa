<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\branch\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách lịch hẹn';
$this->params['breadcrumbs'][] = $this->title;
$beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp(time())->format('Y-m-d 00:00:00'))->getTimestamp();
$endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp(time())->format('Y-m-d 23:59:59'))->getTimestamp();
?>
<div class="branch-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                    <?= Html::a('Tạo mới', ['create','id' => ''], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="form-search-time">
                        <input type="datetime-local" name="start" id="time_start"
                               value="<?= isset($params['AppointmentSearch']['time_start']) && $params['AppointmentSearch']['time_start'] ? date('Y-m-d\TH:i', $params['AppointmentSearch']['time_start']) : date('Y-m-d\TH:i', $beginOfDay) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="datetime-local" name="end" id="time_end"
                               value="<?= isset($params['AppointmentSearch']['time_end']) && $params['AppointmentSearch']['time_end'] ? date('Y-m-d\TH:i', $params['AppointmentSearch']['time_end']) : date('Y-m-d\TH:i', $endOfDay) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>
                    </div>

                    <div class="pull-left" style="margin-top: 15px">
                        <h2 class="pull-left" style="font-size: 18px;margin-right: 15px;color: orange">Số người chưa đến: <?= \common\models\WaitingList::getCount($dataProvider->models, 0) ?></h2>
                        <div class="pull-left" style="margin-right: 15px"> |</div>
                        <h2 class="pull-left" style="font-size: 18px;margin-right: 15px;color: green">Số người đã đến: <?= \common\models\WaitingList::getCount($dataProvider->models, 1) ?></h2>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div style="margin-top: 15px">
                    <button class="btn btn-success pull-left type_view">Xem dạng Lưới</button>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'time',
                                'header' => 'Ngày đến khám',
                                'value' => function ($model) {
                                    return date('d-m-Y H:i:s', $model->time);
                                }
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => function ($modle) {
                                    return \common\models\branch\Branch::findOne($modle->branch_id)->name;
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', ['' => 'Tất cả'] + \common\models\branch\Branch::getBranch(), ['class' => 'form-control'])
                            ],
                            'name',
                            'phone',
                            [
                                'attribute' => 'product_category_id',
                                'label' => 'Nhóm thủ thuật',
                                'value' => 'productCategory.name',
                            ],
                            [
                                'attribute' => 'doctor_id',
                                'value' => 'userAdmin.fullname',
                            ],
                            'description',
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    if ($model->status) {
                                        return '<div class="box-checkbox check" check="1">
                                                    <span class="switchery switcherys updateajax"  data-link="' . Url::to(['updatestatus', 'id' => $model->id]) . '"><small></small></span>
                                                </div>';
                                    }
                                    return '<div class="box-checkbox"  check="0">
                                                <span class="switchery switcherys updateajax" data-link="' . Url::to(['updatestatus', 'id' => $model->id]) . '" ><small></small></span>
                                            </div>';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'status', [1 => 'Đã đến', 0 => 'Chưa đến'], ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{add}{view}{update}{delete}',
                                'buttons' => [
                                    'add' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create?id=' . $model->id, [
                                            'title' => Yii::t('app', 'Thêm lịch hẹn tiếp'),
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
    $('.type_view').click(function () {
        $.ajax({
            type: 'GET',
            cache: false,
            url: '<?= \yii\helpers\Url::to(['set-view']) ?>',
            data: {
                type: 'grid'
            },
            success: function (data) {
                window.location.reload();
            }
        });
    });

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
    });

    jQuery(document).on('click', '.box-checkbox', function () {
        if (confirm("<?= Yii::t('app', 'you_sure_change') ?>")) {
            $(this).css('display', 'none');
            setTimeout(function () {
                $('.box-checkbox').css('display', 'block');
            }, 1000);
            var checkbox = $(this).find('.updateajax').first();
            var label = $(this).find('.switchery').first();
            var ck = $(this);
            var link = checkbox.attr('data-link');

            if (link) {
                jQuery.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: link,
                    data: null,
                    success: function (res) {
                        if (res.code == 200) {
                            checkbox.attr('data-link', res.link);
                            if (!ck.hasClass('check')) {
                                ck.addClass('check');
                            } else {
                                ck.removeClass('check');
                            }
                        }
                        if (res.code == 1) {
                            alert('Không thể chuyển trạng thái khi ngày hẹn chưa đến')
                        }
                    },
                    error: function () {
                    }
                });
            }


        }
        return false;
    });

    function changeHot(_this) {
        var link = _this.attr('data-link');
        if (link) {
            jQuery.ajax({
                type: 'get',
                dataType: 'json',
                url: link,
                data: null,
                success: function (res) {
                    if (res.code == 200) {
                        _this.attr('data-link', res.link);
                    }
                    if (res.code == 1) {
                        alert('Không thể chuyển trạng thái khi ngày hẹn chưa đến')
                    }
                },
                error: function () {
                }
            });
        }
    }
</script>
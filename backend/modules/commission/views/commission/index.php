<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Siteinfo;

/* @var $this yii\web\View */
/* @var $searchModel common\models\commission\CommissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách hoa hồng';
$this->params['breadcrumbs'][] = $this->title;
$time = Siteinfo::findOne(Siteinfo::ROOT_SITE_ID);
?>
<style>
    .waiting {
        background: #ffeece !important;
    }
    th{
        vertical-align: top !important;
    }
</style>
<div class="commission-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                    <div class="form-search-time">
                        <input type="date" name="start" id="time_start"
                               value="<?= isset($params['CommissionSearch']['time_start']) && $params['CommissionSearch']['time_start'] ? date('Y-m-d', $params['CommissionSearch']['time_start']) : date('Y-m-d', time()) ?>">
                        <span>&nbsp; đến ngày &nbsp;</span>
                        <input type="date" name="end" id="time_end"
                               value="<?= isset($params['CommissionSearch']['time_end']) && $params['CommissionSearch']['time_end'] ? date('Y-m-d', $params['CommissionSearch']['time_end']) : date('Y-m-d', time()) ?>">
                        <button class="btn btn-success" id="search">Tìm kiếm</button>
                        <a href="<?= $url ?>" class="btn btn-primary pull-right" style="margin-left: 20px">Xuất
                            Excel</a>
                        <h2 class="pull-right" style="font-weight: bold;font-size: 20px">Tổng
                            tiền: <?= number_format(\common\components\ClaNhakhoa::getTotal($dataProvider->models, 'money')) ?></h2>
                    </div>
                </div>
                <div class="x_content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'type',
                                'value' => function ($model) {
                                    return \common\models\commission\Commission::getType()[$model->type];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'type', \common\models\commission\Commission::getType(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'item_commission_id',
                                'header' => 'Thủ thuật',
                                'value' => function ($model) {
                                    if (isset($model->itemCommission) && $model->itemCommission) {
                                        $product = \common\models\product\Product::findOne($model->itemCommission->product_id);
                                    }
                                    return isset($product) && $product ? $product->name : '';
                                }
                            ],
                            [
                                'attribute' => 'admin_id',
                                'value' => 'userAdmin.fullname',
                            ],
                            [
                                'attribute' => 'type_money',
                                'value' => function ($model) {
                                    return isset(\common\models\commission\Commission::getTypeMoney()[$model->type_money]) ? \common\models\commission\Commission::getTypeMoney()[$model->type_money] : '';
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'type_money', \common\models\commission\Commission::getTypeMoney(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'value',
                                'options' => ['style' => 'width:80px'],
                                'value' => function ($model) {
                                    return number_format($model->value);
                                }
                            ],
                            [
                                'attribute' => 'money',
                                'contentOptions' => function ($model) {
                                    $return = \common\models\commission\Commission::getMoneyWaiting($model);
                                    return $return['waiting'] ? ['class' => 'money-waiting'] : [];
                                },
                                'value' => function ($model) {
                                    $return = \common\models\commission\Commission::getMoneyWaiting($model);
                                    return $return['value'];
                                }
                            ],
                            [
                                'attribute' => 'total_money',
                                'value' => function ($model) {
                                    return number_format($model->total_money);
                                }
                            ],
                            [
                                'attribute' => 'total_money_received',
                                'label' => 'Tiền tính hoa hồng',
                                'value' => function ($model) {
                                    return $model->type == \common\models\commission\Commission::TYPE_MEDICINE ? number_format($model->total_money) : number_format($model->total_money_received);
                                }
                            ],
                            [
                                'attribute' => 'user_id',
                                'value' => 'user.username',
                            ],
                            [
                                'attribute' => 'medical_record_id',
                                'label' => 'Mã HSBA',
                                'content' => function ($model) {
                                    return '<a href="' . \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $model->medical_record_id]) . '">' . $model->medical_record_id . '</a>';
                                }
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => 'branch.name',
                                'filter' => Html::activeDropDownList($searchModel, 'branch_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control', 'prompt' => 'Tất cả'])
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d-m-Y H:i:s', $model->created_at);
                                }
                            ],
//                            [
//                                'class' => 'yii\grid\ActionColumn',
//                                'template' => '{edit}',
//                                'buttons' => [
//                                    'edit' => function ($url, $model) use ($time) {
//                                        $check = \common\components\ClaNhakhoa::checkEditCommission($model->created_at, $time->time_commission);
//                                        if ($check) {
//                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'update?id=' . $model->id, [
//                                                'title' => Yii::t('app', 'Chỉnh sửa'),
//                                            ]);
//                                        }
//                                    },
//                                ],
//                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.money-waiting').parents('tr').addClass('waiting');
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
            },
        });
    })
</script>
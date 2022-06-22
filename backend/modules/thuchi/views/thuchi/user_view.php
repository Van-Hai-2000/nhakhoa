<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\thuchi\ThuChiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lịch sử thanh toán';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý công nợ', 'url' => ['user']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="branch-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 class="pull-left" style="font-weight: bold;font-size: 20px;margin-right: 15px">Tổng
                        tiền: <?= number_format($model->total_money - $model->sale_money) ?> đ</h2>
                    <div class="pull-left" style="margin-right: 30px"></div>
                    <h2 class="pull-right" style="font-weight: bold;font-size: 20px;margin-right: 15px">Chưa thanh
                        toán: <?= number_format($model->total_money - $model->sale_money - $model->money) ?> đ</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Mã hồ sơ bệnh án</th>
                            <th>Số tiền thanh toán</th>
                            <th>Chi nhánh thanh toán</th>
                            <th>% giảm giá</th>
                            <th>Lý do giảm</th>
                            <th>Người thanh toán</th>
                            <th>Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($payments): ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= $payment->medical_record_id ?></td>
                                    <td><?= number_format($payment->money) ?></td>
                                    <td><?= $payment->branch->name ?></td>
                                    <td><?= $payment->pay_sale ?></td>
                                    <td><?= $payment->pay_sale_description ?></td>
                                    <td><?= $payment->userAdmin->username.' - '.$payment->userAdmin->fullname ?></td>
                                    <td><?= date('d-m-Y',$payment->created_at) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
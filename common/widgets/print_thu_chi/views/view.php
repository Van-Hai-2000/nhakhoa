<?php

use yii\helpers\Url;
use common\models\thuchi\ThuChi;

$total_money_payment = 0;
$total_money_payment_more = 0;
$total_money_chi = 0;
$total_money_chi_more = 0;
if(isset($data['branch_id']) && $data['branch_id']){
    $branch = \common\models\branch\Branch::findOne($data['branch_id']);
}
?>
<style>
    .ft1 {
        font: 15px 'Times New Roman';
    }

    .td {
        border-right: #000000 1px solid;
        border-bottom: #000000 1px solid;
        padding: 0px;
        margin: 0px;
        width: 25px;
        vertical-align: bottom;
    }
    .td:first-child {
        border-left: #000000 1px solid;
    }

    .tr {
        height: 19px;
    }

    .td1 {
        border-top: #000000 1px solid;
        text-align: center;
    }

    .p_content {
        text-align: center;
        margin: 10px 0 10px;
    }
</style>
<div style="text-align: left;padding-left: 150px;" class="ft1">
    <h2 style="font-weight: bold;font-size: 18px">CHUỖI PHÒNG KHÁM NHA KHOA HOÀN MỸ - AREUM</h2>
    <label>Địa chỉ:</label> <span><i><?= isset($branch) && $branch ? $branch->address : '' ?></i></span>
    <br>
    <label>Điện thoại:</label> <span><i>02253610107, 0919 067 055</i></span>
    <br>
    <label>Giấy phép KCB:</label> <span><i>số 327/2013/GPHĐ-SYT</i></span>
    <br>
</div>
<div style="border-top: 1px solid black;margin-top: 15px"></div>
<h1 class="ft1" style="font-weight: bold;text-align: center;font-size: 25px;margin-top: 15px">BÁO CÁO TỔNG HỢP THU
    CHI</h1>

<!--Thu tiền khám bệnh-->
<div class="page1">
    <div class="ft1" style="margin-top: 15px">
        <h2 style="font-weight: bold; float: left">THU TIỀN KHÁM BỆNH</h2>
        <h2 style="float: right;font-weight: bold">Từ ngày: <?= $data['time_start'] ?> Đến ngày: <?= $data['time_end'] ?></h2>
    </div>
    <table cellpadding="0" cellspacing="0" class="t0 ft1" style="width: 100%; margin-top: 15px">
        <tbody>
        <tr>
            <td class="tr td td1" style="width: 5%;"><b class="p_content">stt</b></td>
            <td class="tr td td1" style="width: 25%"><b class="p_content">Tên bệnh nhân</b></td>
            <td class="tr td td1" style="width: 20%"><b class="p_content">Địa chỉ</b></td>
            <td class="tr td td1" style="width: 20%"><b class="p_content">Ngày thu</b></td>
            <td class="tr td td1" style="width: 15%"><b class="p_content">Hình thức</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Số tiền</b></td>
        </tr>
        <?php if (isset($data['data']) && $data['data']): $count_payment = 1; ?>
            <?php foreach ($data['data'] as $item): ?>
                <?php if ($item['type'] == ThuChi::TYPE_THU && $item['type_id'] == ThuChi::TYPE_THU_PAYMENT): ?>
                    <tr>
                        <td class="tr td"><p class="p_content"><?= $count_payment ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $item['user']['username'] ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $item['user']['address'] ?></p></td>
                        <td class="tr td"><p class="p_content"><?= date('d-m-y H:i:s',$item['created_at']) ?></p></td>
                        <td class="tr td"><p class="p_content"><?= isset($item['type_payment']) && $item['type_payment'] ? \common\models\user\PaymentHistory::getTypePayment()[$item['type_payment']] : 'Tiền mặt' ?></p></td>
                        <td class="tr td"><p class="p_content"><?= number_format($item['money']) ?></p></td>
                    </tr>
                <?php $count_payment++; $total_money_payment += $item['money']; endif; ?>
                <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold">Tổng cộng</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold"><?= number_format($total_money_payment) ?> đ</p></td>
        </tr>
        </tbody>
    </table>
</div>

<!--Khoản thu khác-->
<div class="page2">
    <div class="ft1" style="margin-top: 15px">
        <h2 style="font-weight: bold; float: left">KHOẢN THU KHÁC</h2>
    </div>
    <table cellpadding="0" cellspacing="0" class="t0 ft1" style="width: 100%; margin-top: 15px">
        <tbody>
        <tr>
            <td class="tr td td1" style="width: 5%;"><b class="p_content">stt</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Tên khoản thu khác</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Người thu</b></td>
            <td class="tr td td1" style="width: 20%"><b class="p_content">Ngày thu</b></td>
            <td class="tr td td1" style="width: 15%"><b class="p_content">Số tiền</b></td>
        </tr>
        <?php if (isset($data['data']) && $data['data']): $count_payment_more = 1; ?>
            <?php foreach ($data['data'] as $item): ?>
                <?php if ($item['type'] == ThuChi::TYPE_THU && $item['type_id'] == ThuChi::TYPE_THU_MORE): ?>
                    <tr>
                        <td class="tr td"><p class="p_content"><?= $count_payment_more ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $item['name'] ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $item['userAdmin']['username'] ?></p></td>
                        <td class="tr td"><p class="p_content"><?= date('d-m-y H:i:s',$item['created_at']) ?></p></td>
                        <td class="tr td"><p class="p_content"><?= number_format($item['money']) ?></p></td>
                    </tr>
                    <?php $count_payment_more++; $total_money_payment_more += $item['money']; endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold">Tổng cộng</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold"><?= number_format($total_money_payment_more) ?> đ</p></td>
        </tr>
        </tbody>
    </table>
</div>

<!--Trả tiền nhà cung cấp-->
<div class="page3">
    <div class="ft1" style="margin-top: 15px">
        <h2 style="font-weight: bold; float: left">CHI TIỀN CHO NHÀ CUNG CẤP</h2>
    </div>
    <table cellpadding="0" cellspacing="0" class="t0 ft1" style="width: 100%; margin-top: 15px">
        <tbody>
        <tr>
            <td class="tr td td1" style="width: 5%;"><b class="p_content">stt</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Tên nhà cung cấp</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Địa chỉ</b></td>
            <td class="tr td td1" style="width: 20%"><b class="p_content">Ngày chi</b></td>
            <td class="tr td td1" style="width: 15%"><b class="p_content">Số tiền</b></td>
        </tr>
        <?php if (isset($data['data']) && $data['data']): $count_chi_ncc = 1; ?>
            <?php foreach ($data['data'] as $item): ?>
                <?php if ($item['type'] == ThuChi::TYPE_CHI && $item['type_id'] == ThuChi::TYPE_CHI_NCC):
                    $ncc = \backend\models\UserAdmin::findOne($item['ncc_id']);
                    ?>
                    <tr>
                        <td class="tr td"><p class="p_content"><?= $count_chi_ncc ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $ncc ? $ncc->username : '' ?></p></td>
                        <td class="tr td"><p class="p_content"><?= '' ?></p></td>
                        <td class="tr td"><p class="p_content"><?= date('d-m-y H:i:s',$item['created_at']) ?></p></td>
                        <td class="tr td"><p class="p_content"><?= number_format($item['money']) ?></p></td>
                    </tr>
                    <?php $count_chi_ncc++; $total_money_chi += $item['money']; endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold">Tổng cộng</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold"><?= number_format($total_money_chi) ?> đ</p></td>
        </tr>
        </tbody>
    </table>
</div>

<!--Khoản chi khác-->
<div class="page4">
    <div class="ft1" style="margin-top: 15px">
        <h2 style="font-weight: bold; float: left">KHOẢN CHI KHÁC</h2>
    </div>
    <table cellpadding="0" cellspacing="0" class="t0 ft1" style="width: 100%; margin-top: 15px">
        <tbody>
        <tr>
            <td class="tr td td1" style="width: 5%;"><b class="p_content">stt</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Tên khoản chi khác</b></td>
            <td class="tr td td1" style="width: 30%"><b class="p_content">Người chi</b></td>
            <td class="tr td td1" style="width: 20%"><b class="p_content">Ngày chi</b></td>
            <td class="tr td td1" style="width: 15%"><b class="p_content">Số tiền</b></td>
        </tr>
        <?php if (isset($data['data']) && $data['data']): $count_chi_more = 1; ?>
            <?php foreach ($data['data'] as $item): ?>
                <?php if ($item['type'] == ThuChi::TYPE_CHI && $item['type_id'] == ThuChi::TYPE_CHI_MORE): ?>
                    <tr>
                        <td class="tr td"><p class="p_content"><?= $count_chi_more ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $item['name'] ?></p></td>
                        <td class="tr td"><p class="p_content"><?= $item['userAdmin']['username'] ?></p></td>
                        <td class="tr td"><p class="p_content"><?= date('d-m-y H:i:s',$item['created_at']) ?></p></td>
                        <td class="tr td"><p class="p_content"><?= number_format($item['money']) ?></p></td>
                    </tr>
                    <?php $count_chi_more++; $total_money_chi_more += $item['money']; endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content">&nbsp;</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold">Tổng cộng</p></td>
            <td class="tr td"><p class="p_content" style="font-weight: bold"><?= number_format($total_money_chi_more) ?> đ</p></td>
        </tr>
        </tbody>
    </table>
</div>

<table cellpadding="0" cellspacing="0" class="t0 ft1" style="width: 100%; margin-top: 15px;text-align: center">
    <tbody>
    <tr>
        <td style="width: 5%;">&nbsp;</td>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 20%;"><b class="p_content">Tổng thu:</b></td>
        <td style="width: 15%;"><b class="p_content"><?= number_format($total_money_payment + $total_money_payment_more) ?> đ</b></td>
    </tr>
    <tr>
        <td style="width: 5%;">&nbsp;</td>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 20%;"><b class="p_content">Tổng chi:</b></td>
        <td style="width: 15%;"><b class="p_content"><?= number_format($total_money_chi + $total_money_chi_more) ?> đ</b></td>
    </tr>
    <tr>
        <td style="width: 5%;">&nbsp;</td>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 30%;">&nbsp;</td>
        <td style="width: 20%;"><b class="p_content">Còn lại:</b></td>
        <td style="width: 15%;"><b class="p_content"><?= number_format($total_money_payment + $total_money_payment_more - $total_money_chi - $total_money_chi_more) ?> đ</b></td>
    </tr>
    </tbody>
</table>

<div class="ft1" style="text-align: center;float: right;margin-top: 20px">
    <p class="p23 ft11 ft1">Ngày <span class="ft10"><?= date('d',time()) ?> </span>Tháng <span class="ft10"><?= date('m',time()) ?> </span>Năm <span class="ft10"><?= date('Y',time()) ?></span>
    </p>
    <p class="p24 ft5" style="font-weight: bold">(Người lập biểu)</p>
</div>
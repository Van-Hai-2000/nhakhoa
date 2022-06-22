<?php
$payment_sale = \common\models\user\PaymentHistory::find()->where(['medical_record_id' => $medical_record_id])->joinWith(['userAdmin'])->all();
$sale = 0;
if ($payment_sale) {
    foreach ($payment_sale as $item) {
        if ($item->pay_sale) {
            if ($item->type_sale == \common\models\user\PaymentHistory::TYPE_PAYMENT_2) {
                $sale += $item->money * $item->pay_sale / 100;
            } else {
                $sale += $item->pay_sale;
            }
        }
    }
}

$thuoc = \common\models\user\MedicalRecordItemMedicine::find()->where(['medical_record_id' => $medical_record_id])->joinWith(['medicine', 'userAdmin'])->asArray()->all();
$lich_hen = \common\models\appointment\Appointment::find()->where(['medical_record_id' => $medical_record_id])->asArray()->all();

$branch = \common\models\branch\Branch::findOne(Yii::$app->user->getIdentity()->branch_id);

?>
<style>
    .tabs {
        padding: 0 15px;
        width: calc(100% - 8px);
    }

    .tabs .title_pro {
        padding-left: 130px;
        margin-bottom: 15px;
    }

    .tabs .logo {
        width: 110px;
        float: left;
        clear: left;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .tabs .logo > img {
        max-width: 100%;
        height: auto;
    }

    .tabs .title_pro ul li {
        margin-bottom: 5px;
    }

    .tabs .title_pro ul li span {
        font-weight: bold;
        font-size: 16px;
    }

    .tabs .title_pro ul li a {
        font-size: 16px;
    }

    .tabs h2 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .tabs h3 {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .tabs p {
        margin: 0;
        line-height: 150%;
    }

    .tabs ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .tabs .title_tabs h1 {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .tabs .title_tabs p {
        text-align: right;
        margin-top: -20px;
        margin-bottom: 0;
        font-size: 16px;
        font-weight: bold;
    }

    .tabs .date {
        text-align: center;
        font-size: 16px;
        margin: 5px 0;
    }

    .tabs .patient_information {
        margin-bottom: 20px;
    }

    .tabs .patient_information .top ul {
        width: 100%;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .tabs .patient_information .top ul li {
        margin-bottom: 5px;
    }

    .tabs .patient_information .top ul li span {
        font-weight: bold;
        font-size: 14px;
    }

    .tabs .patient_information .top ul li i {
        font-size: 16px;
    }

    .tabs table {
        margin-bottom: 20px;
        border-collapse: collapse;
        max-width: calc(100% - 8px) !important;
    }

    .tabs table,
    .tabs table tr,
    .tabs table td {
        border: 1px solid #000;
    }

    .tabs table tr:first-child td {
        font-weight: bold;
    }

    .tabs table td {
        padding: 4px 10px;
    }

    .tabs table tr td p {
        font-size: 14px;
    }

    .tabs .sum {
        width: 100%;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .tabs .sum ul {
        width: 50%;
    }

    .tabs .sum ul li {
        margin-bottom: 10px;
    }

    .tabs .sum ul li i {
        font-size: 16px;
    }

    .tabs .sum ul li span {
        font-size: 14px;
        font-weight: bold;
    }

    .tabs .sum .sum-left {
        text-align: right;
    }

    .tabs .sum .sum-right {
        text-align: center;
    }

    .tabs .PRESCRIPTION {
        min-height: 100px;
    }

    .tabs .calendar_pro {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 20px;
    }

    .tabs .calendar_pro ul:nth-child(1) {
        margin-right: 20px;
    }

    .tabs .calendar_pro ul li {
        font-size: 16px;
    }

    .tabs .PAYMENT_HISTORY {
        margin-bottom: 10px;
    }

    .tabs .PAYMENT_HISTORY ul {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .tabs .PAYMENT_HISTORY ul li {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .tabs .PAYMENT_HISTORY ul li:last-child {
        font-weight: bold;
    }

    .tabs .doct {
        width: 250px;
        margin: auto;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-right: 0;
        text-align: center;
    }

    .tabs .doct .doct-text {
        font-weight: bold;
    }
</style>
<div class="tabs">
    <div class="logo">
        <img src="<?= __SERVER_NAME.'/admin/images/logo_black.png' ?>" alt="logo">
    </div>
    <div class="title_pro">
        <h2>PHÒNG KHÁM NHA KHOA HOÀN MỸ</h2>
        <ul>
            <li>
                <span>Địa chỉ:</span>
                <a><?= isset($branch) && $branch ? $branch->address : '' ?></a>
            </li>
            <li>
                <span>Điện thoại:</span>
                <a>02253610107, 0919 067 055</a>
            </li>
            <li>
                <span>Giấy phép KCB:</span>
                <a>số 327/2013/GPHĐ-SYT</a>
            </li>
        </ul>
    </div>
    <div class="title_tabs">
        <h1>PHIẾU KHÁM BỆNH</h1>
        <p style="font-size: 14px"><i>Số hồ sơ: <?= $medical_record_id ?></i></p>
    </div>
    <p class="date"><i>Ngày khám:<?= date('d/m/Y', time()) ?></i></p>
    <div class="patient_information">
        <div class="top">
            <ul>
                <li>
                    <i>Họ và tên:</i>
                    <span><?= $data['hoso']['user']['username'] ?></span>
                </li>
                <li>
                    <i>Giới tính:</i>
                    <span><?= $data['hoso']['user']['sex'] ? \common\models\user\User::getSex()[$data['hoso']['user']['sex']] : 'Không xác định' ?></span>
                </li>
                <li>
                    <i>Năm sinh:</i>
                    <span><?= $data['hoso']['user']['birthday'] ? date('d/m/Y', $data['hoso']['user']['birthday']) : 'Đang cập nhật' ?></span>
                </li>
            </ul>
            <ul>
                <li>
                    <i>Địa chỉ:</i>
                    <span><?= $data['hoso']['user']['address'] ?></span>
                </li>
                <li>
                    <i>Điện thoại:</i>
                    <span><?= $data['hoso']['user']['phone'] ?></span>
                </li>
            </ul>
        </div>
    </div>

    <div style="text-align: left">
        <h3>Thủ thuật đã thực hiện</h3>
        <table>
            <tbody>
            <tr>
                <td>
                    <p>Tên thủ thuật</p>
                </td>
                <td>
                    <p>Nội dung thủ thuật</p>
                </td>
                <td>
                    <p>SL</p>
                </td>
                <td>
                    <p>Đ.giá</p>
                </td>
                <td>
                    <p>Giảm</p>
                </td>
                <td>
                    <p>T.toán</p>
                </td>
            </tr>
            <?php if ($data['products']): ?>
                <?php foreach ($data['products'] as $product): ?>
                    <tr>
                        <td>
                            <p><?= $product['product']['name'] ?></p>
                        </td>
                        <td>
                            <p><?= $product['note'] ?></p>
                        </td>
                        <td>
                            <p><?= $product['quantity'] ?></p>
                        </td>
                        <td>
                            <p><?= $product['product']['price_market'] ? number_format($product['product']['price_market']) : number_format($product['product']['price']) ?></p>
                        </td>
                        <td>
                            <p><?= $product['product']['price_market'] ? number_format(($product['product']['price_market'] - $product['product']['price']) * $product['quantity']) : 0 ?></p>
                        </td>
                        <td>
                            <p><?= number_format($product['product']['price'] * $product['quantity']) ?></p>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <?php if ($data['chua_kham']): ?>
            <h3>Thủ thuật chưa thực hiện</h3>
            <table>
                <tbody>
                <tr>
                    <td>
                        <p>Tên thủ thuật</p>
                    </td>
                    <td>
                        <p>Nội dung thủ thuật</p>
                    </td>
                    <td>
                        <p>SL</p>
                    </td>
                    <td>
                        <p>Đ.giá</p>
                    </td>
                    <td>
                        <p>Giảm</p>
                    </td>
                    <td>
                        <p>T.toán</p>
                    </td>
                </tr>
                <?php foreach ($data['chua_kham'] as $datum): ?>
                    <?php if ($datum['quantity'] > $datum['quantity_use']): ?>
                        <tr>
                            <td>
                                <p><?= $datum['product']['name'] ?></p>
                            </td>
                            <td>
                                <p></p>
                            </td>
                            <td>
                                <p><?= $datum['quantity'] - $datum['quantity_use'] ?></p>
                            </td>
                            <td>
                                <p><?= number_format($datum['product']['price']) ?></p>
                            </td>
                            <td>
                                <p><?= $datum['product']['price_market'] ? number_format(($datum['product']['price_market'] - $datum['product']['price']) * $datum['quantity']) : 0 ?></p>
                            </td>
                            <td>
                                <p><?= number_format($datum['product']['price'] * ($datum['quantity'] - $datum['quantity_use'])) ?></p>
                            </td>

                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="PRESCRIPTION" style="text-align: left; display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start">
        <h3>ĐƠN THUỐC</h3>
        <table>
            <tbody>
            <tr>
                <td>
                    <p>Tên thuốc</p>
                </td>
                <td>
                    <p>SL</p>
                </td>
                <td>
                    <p>Đ.giá</p>
                </td>
                <td>
                    <p>T.toán</p>
                </td>
                <td>
                    <p>BS kê đơn</p>
                </td>
            </tr>
            <?php if ($thuoc): ?>
                <?php foreach ($thuoc as $value): ?>
                    <tr>
                        <td>
                            <p><?= $value['medicine']['name'] ?></p>
                        </td>
                        <td>
                            <p><?= $value['quantity'] ?></p>
                        </td>
                        <td>
                            <p><?= number_format($value['money']) ?></p>
                        </td>
                        <td>
                            <p><?= number_format($value['money'] * $value['quantity']) ?></p>
                        </td>
                        <td>
                            <p><?= isset($value['userAdmin']) && $value['userAdmin'] ? $value['userAdmin']['fullname'] : '' ?></p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="sum">
        <ul class="sum-left">
            <li>
                <i>Tổng phải thanh toán:</i>
                <span><?= number_format($data['hoso']['total_money']) ?> đ</span>
            </li>
            <li>
                <i>Đã thanh toán: </i>
                <span><?= number_format($data['hoso']['money']) ?> đ</span>
            </li>
        </ul>
        <ul class="sum-right">
            <li>
                <i>Tổng giảm giá:</i>
                <?php $hoso_salemoney = isset($data['hoso']['sale_money']) && $data['hoso']['sale_money'] ? $data['hoso']['sale_money'] : 0; ?>
                <span><?= number_format( floatval($hoso_salemoney) + floatval($sale)) ?> đ</span>
            </li>
            <li>
                <i>Còn lại: </i>
                <span><?= number_format($data['hoso']['total_money'] - $data['hoso']['money'] - $data['hoso']['sale_money']) ?>
                    đ</span>
            </li>
        </ul>
    </div>
    <div class="calendar_pro">
        <ul>
            <h3>LỊCH HẸN</h3>
            <?php if ($lich_hen): ?>
                <?php foreach ($lich_hen as $value): ?>
                    <li><?= date('d/m/Y H:i:s', $value['time']) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <ul>
            <h3>Ghi chú</h3>
            <?php if ($lich_hen): ?>
                <?php foreach ($lich_hen as $value): ?>
                    <li><?= $value['description'] ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="PAYMENT_HISTORY">
        <h3>LỊCH SỬ THANH TOÁN</h3>
        <div class="PRESCRIPTION" style="text-align: left">
            <table>
                <tbody>
                <tr>
                    <td>
                        <p>Thời gian</p>
                    </td>
                    <td>
                        <p>Người thanh toán</p>
                    </td>
                    <td>
                        <p>Tổng tiền</p>
                    </td>
                    <td>
                        <p>Giảm giá</p>
                    </td>
                    <td>
                        <p>Thực thu</p>
                    </td>
                </tr>
                <?php if ($payment_sale): ?>
                    <?php foreach ($payment_sale as $value):
                        $sl = $value['pay_sale'];
                        if ($value['type_sale'] == \common\models\user\PaymentHistory::TYPE_SALE_2) {
                            $sl = $value['money'] * $value['pay_sale'] / 100;
                        }
                        ?>
                        <tr>
                            <td>
                                <p><?= date('d/m/Y H:i:s', $value['created_at']) ?></p>
                            </td>
                            <td>
                                <p><?= $value['userAdmin']['fullname'] ?></p>
                            </td>
                            <td>
                                <p><?= number_format($value['money']) ?> đ</p>
                            </td>
                            <td>
                                <p><?= number_format($sl) ?> đ</p>
                            </td>
                            <td>
                                <p><?= number_format($value['money'] - $sl) ?>đ</p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <p style="margin: 0; margin-bottom: 8px; line-height: 150%">* Chú ý: Lần sau đi khám vui lòng mang theo phiếu khám bệnh này !</p>
    <div class="doct">
        <p>Ngày <?= date('d', time()) ?> Tháng <?= date('m', time()) ?> Năm <?= date('Y', time()) ?></p>
        <p class="doct-text">Bác sĩ khám bệnh</p>
    </div>
</div>

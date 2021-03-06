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
        <h2>PH??NG KH??M NHA KHOA HO??N M???</h2>
        <ul>
            <li>
                <span>?????a ch???:</span>
                <a><?= isset($branch) && $branch ? $branch->address : '' ?></a>
            </li>
            <li>
                <span>??i???n tho???i:</span>
                <a>02253610107, 0919 067 055</a>
            </li>
            <li>
                <span>Gi???y ph??p KCB:</span>
                <a>s??? 327/2013/GPH??-SYT</a>
            </li>
        </ul>
    </div>
    <div class="title_tabs">
        <h1>PHI???U KH??M B???NH</h1>
        <p style="font-size: 14px"><i>S??? h??? s??: <?= $medical_record_id ?></i></p>
    </div>
    <p class="date"><i>Ng??y kh??m:<?= date('d/m/Y', time()) ?></i></p>
    <div class="patient_information">
        <div class="top">
            <ul>
                <li>
                    <i>H??? v?? t??n:</i>
                    <span><?= $data['hoso']['user']['username'] ?></span>
                </li>
                <li>
                    <i>Gi???i t??nh:</i>
                    <span><?= $data['hoso']['user']['sex'] ? \common\models\user\User::getSex()[$data['hoso']['user']['sex']] : 'Kh??ng x??c ?????nh' ?></span>
                </li>
                <li>
                    <i>N??m sinh:</i>
                    <span><?= $data['hoso']['user']['birthday'] ? date('d/m/Y', $data['hoso']['user']['birthday']) : '??ang c???p nh???t' ?></span>
                </li>
            </ul>
            <ul>
                <li>
                    <i>?????a ch???:</i>
                    <span><?= $data['hoso']['user']['address'] ?></span>
                </li>
                <li>
                    <i>??i???n tho???i:</i>
                    <span><?= $data['hoso']['user']['phone'] ?></span>
                </li>
            </ul>
        </div>
    </div>

    <div style="text-align: left">
        <h3>Th??? thu???t ???? th???c hi???n</h3>
        <table>
            <tbody>
            <tr>
                <td>
                    <p>T??n th??? thu???t</p>
                </td>
                <td>
                    <p>N???i dung th??? thu???t</p>
                </td>
                <td>
                    <p>SL</p>
                </td>
                <td>
                    <p>??.gi??</p>
                </td>
                <td>
                    <p>Gi???m</p>
                </td>
                <td>
                    <p>T.to??n</p>
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
            <h3>Th??? thu???t ch??a th???c hi???n</h3>
            <table>
                <tbody>
                <tr>
                    <td>
                        <p>T??n th??? thu???t</p>
                    </td>
                    <td>
                        <p>N???i dung th??? thu???t</p>
                    </td>
                    <td>
                        <p>SL</p>
                    </td>
                    <td>
                        <p>??.gi??</p>
                    </td>
                    <td>
                        <p>Gi???m</p>
                    </td>
                    <td>
                        <p>T.to??n</p>
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
        <h3>????N THU???C</h3>
        <table>
            <tbody>
            <tr>
                <td>
                    <p>T??n thu???c</p>
                </td>
                <td>
                    <p>SL</p>
                </td>
                <td>
                    <p>??.gi??</p>
                </td>
                <td>
                    <p>T.to??n</p>
                </td>
                <td>
                    <p>BS k?? ????n</p>
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
                <i>T???ng ph???i thanh to??n:</i>
                <span><?= number_format($data['hoso']['total_money']) ?> ??</span>
            </li>
            <li>
                <i>???? thanh to??n: </i>
                <span><?= number_format($data['hoso']['money']) ?> ??</span>
            </li>
        </ul>
        <ul class="sum-right">
            <li>
                <i>T???ng gi???m gi??:</i>
                <?php $hoso_salemoney = isset($data['hoso']['sale_money']) && $data['hoso']['sale_money'] ? $data['hoso']['sale_money'] : 0; ?>
                <span><?= number_format( floatval($hoso_salemoney) + floatval($sale)) ?> ??</span>
            </li>
            <li>
                <i>C??n l???i: </i>
                <span><?= number_format($data['hoso']['total_money'] - $data['hoso']['money'] - $data['hoso']['sale_money']) ?>
                    ??</span>
            </li>
        </ul>
    </div>
    <div class="calendar_pro">
        <ul>
            <h3>L???CH H???N</h3>
            <?php if ($lich_hen): ?>
                <?php foreach ($lich_hen as $value): ?>
                    <li><?= date('d/m/Y H:i:s', $value['time']) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <ul>
            <h3>Ghi ch??</h3>
            <?php if ($lich_hen): ?>
                <?php foreach ($lich_hen as $value): ?>
                    <li><?= $value['description'] ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="PAYMENT_HISTORY">
        <h3>L???CH S??? THANH TO??N</h3>
        <div class="PRESCRIPTION" style="text-align: left">
            <table>
                <tbody>
                <tr>
                    <td>
                        <p>Th???i gian</p>
                    </td>
                    <td>
                        <p>Ng?????i thanh to??n</p>
                    </td>
                    <td>
                        <p>T???ng ti???n</p>
                    </td>
                    <td>
                        <p>Gi???m gi??</p>
                    </td>
                    <td>
                        <p>Th???c thu</p>
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
                                <p><?= number_format($value['money']) ?> ??</p>
                            </td>
                            <td>
                                <p><?= number_format($sl) ?> ??</p>
                            </td>
                            <td>
                                <p><?= number_format($value['money'] - $sl) ?>??</p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <p style="margin: 0; margin-bottom: 8px; line-height: 150%">* Ch?? ??: L???n sau ??i kh??m vui l??ng mang theo phi???u kh??m b???nh n??y !</p>
    <div class="doct">
        <p>Ng??y <?= date('d', time()) ?> Th??ng <?= date('m', time()) ?> N??m <?= date('Y', time()) ?></p>
        <p class="doct-text">B??c s?? kh??m b???nh</p>
    </div>
</div>

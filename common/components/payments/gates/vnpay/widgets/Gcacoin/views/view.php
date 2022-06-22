<?php
$banks = \common\models\BankAdmin::find()->orderBy('isdefault DESC')->all();
$bank_df = $banks ? $banks[0] : [];
$model = new \common\models\gcacoin\Recharge();
?>
<div class="payment-method">
    <div class="col-xs-12 payment">
        <ul class="list-content">
            <?php if ($banks) { ?>
                <style>
                    .list-content .nice-select {
                        width: 100%;
                        margin-bottom: 10px;
                    }

                    .list-content .nice-select .list {
                        width: 100%;
                    }

                    .create-page-store .form-create-store {
                        padding-bottom: 30px;
                    }
                </style>
                <input type="hidden" name="Order[key]" value="<?= $data['code'] ?>">
                <li class="active">
                    <label><input type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_CK; ?>" name="Order[payment_method]" checked="checked">Chuyển khoản</label>
                    <div class="boxContent">
                        <select name="Order[payment_method_child]" id="bank_id">
                            <?php foreach ($banks as $bank) { ?>
                                <option value="<?= $bank->id ?>" data-name="<?= $bank->bank_name ?>" data-uname="<b><?= $bank->user_name ?>" data-code="<?= $bank->number ?>" data-add="<?= $bank->address ?>"><?= $bank->bank_name ?></option>
                            <?php } ?>
                        </select>
                        <div class="info">
                            <ul>
                                <li>Tên ngân hàng: <b class="data-name"><?= $bank_df->bank_name ?></b></li>
                                <li>Số tài khoản: <b class="data-code"><?= $bank_df->number ?></b></li>
                                <li>Chủ tài khoản: <b class="data-uname"><?= $bank_df->user_name ?></b></li>
                                <li>Chi nhánh: <b class="data-add"><?= $bank_df->address ?></b></li>
                            </ul>
                            <div>
                                <?=
                                    \frontend\widgets\form\FormWidget::widget([
                                        'view' => 'form-img-3',
                                        'input' => [
                                            'model' => $model,
                                            'id' => 'avatar_1',
                                            'images' => null,
                                            'script' => '<script src="' . Yii::$app->homeUrl . 'js/upload/ajaxupload.min.js"></script>'
                                        ]
                                    ]);
                                ?>
                                 <?=
                                    \frontend\widgets\form\FormWidget::widget([
                                        'view' => 'form-img-3',
                                        'input' => [
                                            'model' => $model,
                                            'id' => 'avatar_2',
                                            'images' => null,
                                            'script' => '<script src="' . Yii::$app->homeUrl . 'js/upload/ajaxupload.min.js"></script>'
                                        ]
                                    ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
                <script>
                    $(document).on('change', '#bank_id', function() {
                        sl = $("#bank_id option:selected");
                        $('.data-name').html(sl.attr('data-name'));
                        $('.data-code').html(sl.attr('data-code'));
                        $('.data-uname').html(sl.attr('data-uname'));
                        $('.data-add').html(sl.attr('data-add'));
                    })
                </script>
            <?php } ?>
            <!-- <li>
                <label>
                    <input type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_VNPay; ?>" name="Order[payment_method]"><?= Yii::t('app', 'internet_banking_method') ?>
                </label>
                <div class="boxContent">
                    <p>
                        <i>
                            <span style="color:#ff5a00;font-weight:bold;text-decoration:underline;"><?= Yii::t('app', 'note') ?></span>: <?= Yii::t('app', 'note_internet_banking_method') ?>
                        </i>
                    </p>
                    <ul class="cardList clearfix">

                        <?php
                        $banks = $methods['banks'];
                        $banks = false;
                        if ($banks && count($banks)) {
                            foreach ($banks as $key => $name) {
                        ?>
                                <li class="bank-online-methods">
                                    <label for="atm_ck_on">
                                        <i class="<?= $key ?>" title="<?= $name ?>"></i>
                                        <input type="radio" value="<?= $key ?>" name="Order[payment_method_child]">
                                    </label>
                                </li>
                        <?php
                            }
                        }
                        ?>
                        <li>
                            <label for="NCB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/ncb_logo.png" width="200" height="40" alt="NCB">
                                <input checked="checked" type="radio" name="Order[payment_method_child]" value="NCB" id="NCB">
                            </label>
                        </li>
                        <li>
                            <label for="VIETCOMBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/vietcombank_logo.png" width="200" height="40" alt="VIETCOMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="VIETCOMBANK" id="VIETCOMBANK">

                            </label>
                        </li>
                        <li>
                            <label for="VIETINBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/vietinbank_logo.png" width="200" height="40" alt="VIETINBANK">
                                <input type="radio" name="Order[payment_method_child]" value="VIETINBANK" id="VIETINBANK">
                            </label>
                        </li>
                        <li>
                            <label for="BIDV">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/bidv_logo.png" width="200" height="40" alt="BIDV">
                                <input type="radio" name="Order[payment_method_child]" value="BIDV" id="BIDV">
                            </label>
                        </li>
                        <li>
                            <label for="AGRIBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/agribank_logo.png" width="200" height="40" alt="AGRIBANK">
                                <input type="radio" name="Order[payment_method_child]" value="AGRIBANK" id="AGRIBANK">
                            </label>
                        </li>
                        <li>
                            <label for="SACOMBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/sacombank_logo.png" width="200" height="40" alt="SACOMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="SACOMBANK" id="SACOMBANK">
                            </label>
                        </li>
                        <li>
                            <label for="TECHCOMBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/techcombank_logo.png" width="200" height="40" alt="TECHCOMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="TECHCOMBANK" id="TECHCOMBANK">
                            </label>
                        </li>
                        <li>
                            <label for="ACB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/acb_logo.png" width="200" height="40" alt="ACB">
                                <input type="radio" name="Order[payment_method_child]" value="ACB" id="ACB">
                            </label>
                        </li>
                        <li>
                            <label for="VPBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/vpbank_logo.png" width="200" height="40" alt="VPBANK">
                                <input type="radio" name="Order[payment_method_child]" value="VPBANK" id="VPBANK">
                            </label>
                        </li>
                        <li>
                            <label for="SHB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/shb_logo.png" width="200" height="40" alt="SHB">
                                <input type="radio" name="Order[payment_method_child]" value="SHB" id="SHB">
                            </label>
                        </li>
                        <li>
                            <label for="DONGABANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/dongabank_logo.png" width="200" height="40" alt="DONGABANK">
                                <input type="radio" name="Order[payment_method_child]" value="DONGABANK" id="DONGABANK">
                            </label>
                        </li>
                        <li>
                            <label for="EXIMBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/eximbank_logo.png" width="200" height="40" alt="EXIMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="EXIMBANK" id="EXIMBANK">
                            </label>
                        </li>
                        <li>
                            <label for="TPBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/tpbank_logo.png" width="200" height="40" alt="TPBANK">
                                <input type="radio" name="Order[payment_method_child]" value="TPBANK" id="TPBANK">
                            </label>
                        </li>
                        <li>
                            <label for="OJB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/oceanbank_logo.png" width="200" height="40" alt="OJB">
                                <input type="radio" name="Order[payment_method_child]" value="OJB" id="OJB">
                            </label>
                        </li>
                        <li>
                            <label for="MSBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/msbank_logo.png" width="200" height="40" alt="MSBANK">
                                <input type="radio" name="Order[payment_method_child]" value="MSBANK" id="MSBANK"></label>
                        </li>
                        <li>
                            <label for="HDBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/hdbank_logo.png" width="200" height="40" alt="HDBANK">
                                <input type="radio" name="Order[payment_method_child]" value="HDBANK" id="HDBANK"></label>
                        </li>
                        <li>
                            <label for="NAMABANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/namabank_logo.png" width="200" height="40" alt="NAMABANK">
                                <input type="radio" name="Order[payment_method_child]" value="NAMABANK" id="NAMABANK"></label>
                        </li>
                        <li>
                            <label for="OCB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/ocb_logo.png" width="200" height="40" alt="OCB">
                                <input type="radio" name="Order[payment_method_child]" value="OCB" id="OCB"></label>
                        </li>
                        <li>
                            <label for="SCB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/scb_logo.png" width="200" height="40" alt="SCB">
                                <input type="radio" name="Order[payment_method_child]" value="SCB" id="SCB"></label>
                        </li>
                        <li>
                            <label for="ABBANK">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/abbank_logo.png" width="200" height="40" alt="ABBANK">
                                <input type="radio" name="Order[payment_method_child]" value="ABBANK" id="ABBANK"></label>
                        </li>
                        <li>
                            <label for="IVB">
                                <img src="https://umove.com.vn/themes/introduce/w3ni477/images/ivb_logo.png" width="200" height="40" alt="IVB">
                                <input type="radio" name="Order[payment_method_child]" value="IVB" id="IVB"></label>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="">
                <label><input type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_VNPay; ?>" name="Order[payment_method]"><?= Yii::t('app', 'visa_masterCard_method') ?></label>
                <div class="boxContent">
                    <p><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;"><?= Yii::t('app', 'note') ?></span>: Visa, MasterCard.</p>
                    <ul class="cardList clearfix">
                        <?php
                        $visas = isset($methods['visas']) ? $methods['visas'] : array();
                        if ($visas && count($visas)) {
                            foreach ($visas as $key => $name) { ?>
                                <li class="bank-online-methods">
                                    <label for="atm_ck_on">
                                        <i class="<?php echo strtolower($key) . 'card'; ?>" title="<?= $name ?>"></i>
                                        <input type="radio" value="VISA" name="Order[payment_method_child]">
                                    </label>
                                </li>
                        <?php  }
                        }
                        ?>
                    </ul>
                </div>
            </li> -->
            <p>
                <i>
                    <span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span>: Trường hợp bạn không sử dụng Internet Banking bạn có thể đến nạp V trực tiếp tại văn phòng công ty.
                </i>
            </p>
        </ul>
        <script language="javascript">
            $('input[name="Order[payment_method]"]').bind('click', function() {
                $('.list-content li').removeClass('active');
                $(this).parent().parent('li').addClass('active');
            });
        </script>
    </div>
</div>
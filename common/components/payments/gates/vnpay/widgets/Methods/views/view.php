<style>
    .disabledbutton {
        pointer-events: none;
        opacity: 0.4;
    }
</style>
<div class="payment-method">
    <div class="col-xs-12 payment">
        <ul class="list-content">
            <li class="">
                <label><input checked="" id="input-defaunt" type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_NR; ?>" name="Order[payment_method]" ><?= Yii::t('app', 'cash_method') ?></label>
            </li>
            <li class="">
                <label><input type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_QR; ?>" name="Order[payment_method]"><?= Yii::t('app', 'qr_method') ?></label>
            </li>
            <li class="">
                <label><input type="radio" onclick="checkMoney()" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_MEMBERIN; ?>" name="Order[payment_method]" ><?= Yii::t('app', 'member_method') ?></label>
            </li> 
            <li class="disabledbutton">
                <label>
                    <input type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_VNPay; ?>" name="Order[payment_method]"><?= Yii::t('app', 'internet_banking_method') ?>(Cập nhật)
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
                            <label for="VIETCOMBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/vietcombank_logo.png" width="200" height="40" alt="VIETCOMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="VIETCOMBANK" id="VIETCOMBANK">

                            </label>
                        </li>
                        <li>
                            <label for="VIETINBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/vietinbank_logo.png" width="200" height="40" alt="VIETINBANK">
                                <input type="radio" name="Order[payment_method_child]" value="VIETINBANK" id="VIETINBANK">
                            </label>
                        </li>
                        <li>
                            <label for="BIDV">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/bidv_logo.png" width="200" height="40" alt="BIDV">
                                <input type="radio" name="Order[payment_method_child]" value="BIDV" id="BIDV">
                            </label>
                        </li>
                        <li>
                            <label for="AGRIBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/agribank_logo.png" width="200" height="40" alt="AGRIBANK">
                                <input type="radio" name="Order[payment_method_child]" value="AGRIBANK" id="AGRIBANK">
                            </label>
                        </li>
                        <li>
                            <label for="SACOMBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/sacombank_logo.png" width="200" height="40" alt="SACOMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="SACOMBANK" id="SACOMBANK">
                            </label>
                        </li>
                        <li>
                            <label for="TECHCOMBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/techcombank_logo.png" width="200" height="40" alt="TECHCOMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="TECHCOMBANK" id="TECHCOMBANK">
                            </label>
                        </li>
                        <li>
                            <label for="ACB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/acb_logo.png" width="200" height="40" alt="ACB">
                                <input type="radio" name="Order[payment_method_child]" value="ACB" id="ACB">
                            </label>
                        </li>
                        <li>
                            <label for="VPBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/vpbank_logo.png" width="200" height="40" alt="VPBANK">
                                <input type="radio" name="Order[payment_method_child]" value="VPBANK" id="VPBANK">
                            </label>
                        </li>
                        <li>
                            <label for="SHB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/shb_logo.png" width="200" height="40" alt="SHB">
                                <input type="radio" name="Order[payment_method_child]" value="SHB" id="SHB">
                            </label>
                        </li>
                        <li>
                            <label for="DONGABANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/dongabank_logo.png" width="200" height="40" alt="DONGABANK">
                                <input type="radio" name="Order[payment_method_child]" value="DONGABANK" id="DONGABANK">
                            </label>
                        </li>
                        <li>
                            <label for="EXIMBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/eximbank_logo.png" width="200" height="40" alt="EXIMBANK">
                                <input type="radio" name="Order[payment_method_child]" value="EXIMBANK" id="EXIMBANK">
                            </label>
                        </li>
                        <li>
                            <label for="TPBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/tpbank_logo.png" width="200" height="40" alt="TPBANK">
                                <input type="radio" name="Order[payment_method_child]" value="TPBANK" id="TPBANK">
                            </label>
                        </li>
                        <li>
                            <label for="NCB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/ncb_logo.png" width="200" height="40" alt="NCB">
                                <input type="radio" name="Order[payment_method_child]" value="NCB" id="NCB">
                            </label>
                        </li>
                        <li>
                            <label for="OJB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/oceanbank_logo.png" width="200" height="40" alt="OJB">
                                <input type="radio" name="Order[payment_method_child]" value="OJB" id="OJB">
                            </label>
                        </li>
                        <li>
                            <label for="MSBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/msbank_logo.png" width="200" height="40" alt="MSBANK">
                                <input type="radio" name="Order[payment_method_child]" value="MSBANK" id="MSBANK"></label>
                        </li>
                        <li>
                            <label for="HDBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/hdbank_logo.png" width="200" height="40" alt="HDBANK">
                                <input type="radio" name="Order[payment_method_child]" value="HDBANK" id="HDBANK"></label>
                        </li>
                        <li>
                            <label for="NAMABANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/namabank_logo.png" width="200" height="40" alt="NAMABANK">
                                <input type="radio" name="Order[payment_method_child]" value="NAMABANK" id="NAMABANK"></label>
                        </li>
                        <li>
                            <label for="OCB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/ocb_logo.png" width="200" height="40" alt="OCB">
                                <input type="radio" name="Order[payment_method_child]" value="OCB" id="OCB"></label>
                        </li>
                        <li>
                            <label for="SCB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/scb_logo.png" width="200" height="40" alt="SCB">
                                <input type="radio" name="Order[payment_method_child]" value="SCB" id="SCB"></label>
                        </li>
                        <li>
                            <label for="ABBANK">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/abbank_logo.png" width="200" height="40" alt="ABBANK">
                                <input type="radio" name="Order[payment_method_child]" value="ABBANK" id="ABBANK"></label>
                        </li>
                        <li>
                            <label for="IVB">
                                <img src="<?= Yii::$app->homeUrl ?>images/bank/ivb_logo.png" width="200" height="40" alt="IVB">
                                <input type="radio" name="Order[payment_method_child]" value="IVB" id="IVB"></label>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="disabledbutton">
                <label><input type="radio" value="<?= \common\components\payments\ClaPayment::PAYMENT_METHOD_VNPay; ?>" name="Order[payment_method]" selected="true"><?= Yii::t('app', 'visa_masterCard_method') ?>(Cập nhật)</label>
                <div class="boxContent">
                    <p><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;"><?= Yii::t('app', 'note') ?></span>: Visa, MasterCard.</p>
                    <ul class="cardList clearfix">
                        <?php
                        $visas = isset($methods['visas']) ? $methods['visas'] : array();
                        if ($visas && count($visas)) {

                            foreach ($visas as $key => $name) {
                        ?>
                                <li class="bank-online-methods">
                                    <label for="atm_ck_on">
                                        <i class="<?php echo strtolower($key) . 'card'; ?>" title="<?= $name ?>"></i>
                                        <input type="radio" value="VISA" name="Order[payment_method_child]">
                                    </label>
                                </li>
                                <?php
                            }
                        }
                                ?>
                    </ul>
                </div>
            </li>
        </ul>
        <script language="javascript">
            $('input[name="Order[payment_method]"]').bind('click', function() {
                $('.list-content li').removeClass('active');
                $(this).parent().parent('li').addClass('active');
            });
        </script>
    </div>
</div>
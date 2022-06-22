<div class="payment-method">
    <div class="col-xs-12 payment">
        <ul class="list-content">
            <li class="active">
                <label><input type="radio" value="2" name="Order[payment_method]" checked=""><?= Yii::t('app', 'cash_method') ?> (COD)</label>
            </li>
            <li>
                <label>
                    <input type="radio" value="ATM_ONLINE" name="Order[payment_method]"><?= Yii::t('app', 'internet_banking_method') ?>
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
                    </ul>
                </div>
            </li>
            <li class="">
                <label><input type="radio" value="VISA" name="Order[payment_method]" selected="true"><?= Yii::t('app', 'visa_masterCard_method') ?></label>
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
                                        <i class="<?php echo strtolower($key).'card'; ?>" title="<?=$name?>"></i>
                                        <input type="radio" value="<?=$key?>" name="Order[payment_method_child]">
                                    </label>
                                </li>
                            <?php }
                        }
                        ?>
                    </ul>
                </div>
            </li>
        </ul>
        <script language="javascript">
            $('input[name="Order[payment_method]"]').bind('click', function () {
                $('.list-content li').removeClass('active');
                $(this).parent().parent('li').addClass('active');
            });
        </script>
    </div>
</div>
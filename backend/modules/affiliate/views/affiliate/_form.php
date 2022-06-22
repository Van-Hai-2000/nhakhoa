<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$text_opt = $user->getTextOtp();

/* @var $this yii\web\View */
/* @var $model common\models\affiliate\AffiliateConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="affiliate-config-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'otp-form',
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                        'fieldClass' => 'common\components\MyActiveField'
                    ]);
                    ?>

                    <?= $form->field($model, 'sale_for_app_status')->dropDownList(['1' => 'bật', '0' => 'Tắt']) ?>

                    <?= $form->field($model, 'sale_for_app_value')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'sale_befor_app_status')->dropDownList(['1' => 'bật', '0' => 'Tắt']) ?>

                    <?= $form->field($model, 'sale_befor_app_value')->textInput(['maxlength' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'submit-form']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="box-responce" class="hidden"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#otp-form').submit(function() {
            text0 = '<?= $text_opt[0] ?>';
            text1 = '<?= $text_opt[1] ?>';
            _this = $(this);
            if (_this.attr('otp') != '1') {
                promptCS(text0, text1);
                $('#PromptCSInput').attr('type', 'password');
                yesPrompt = function(otp, data) {
                    if (otp != null) {
                        $.ajax({
                            url: '<?= \yii\helpers\Url::to(['/gcacoin/otp/check-pass2']) ?>',
                            type: 'POST',
                            data: {
                                otp: otp,
                            },
                            success: function(dt) {
                                if (dt == 'success') {
                                    _this.attr('otp', '1');
                                    _this.submit();
                                } else {
                                    promptCS(text0 + '<p class="error">Mật khẩu cấp 2 không đúng.</p>', text1);
                                    $('#PromptCSInput').attr('type', 'password');
                                }
                            }

                        });
                    }
                }
                return false;
            }
        })
    })
</script>
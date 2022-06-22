<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = 'Nạp OCOP V';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

$text_opt = $user->getTextOtp();
?>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Nạp V cho tài khoản ID<?= $user->id ?>: <span style="color: green;"><?= $user->username ?></span></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="w0" class="grid-view">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'product-form',
                                'options' => [
                                    'class' => 'form-horizontal'
                                ]
                            ]);
                            ?>
                            <div class="form-group field-recharge-value required has-success">
                                <label class="control-label" for="recharge-value">Số V cần nạp</label>
                                <input type="text" id="recharge-value" class="form-control" name="value" aria-required="true" aria-invalid="false">
                                <div class="help-block"></div>
                            </div>
                            <?= $this->render('partial/image', ['form' => $form, 'model' => $model, 'images' => $images]); ?>
                            <div align="center">
                                <a class="btn btn-primary" onclick="checkrecharge(this)" data-user-id="<?= $user->id ?>">Xác
                                    nhận</a>
                                <a href="<?= \yii\helpers\Url::to(['recharge/index']) ?>" class="btn btn-danger">Hủy</a>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function recharge(data) {
        text0 = '<?= $text_opt[0] ?>';
        text1 = '<?= $text_opt[1] ?>';
        promptCS(text0, text1, data);
        $('#PromptCSInput').attr('type', 'password');
        yesPrompt = function(otp, data,) {
            if (otp != null) {
                $.ajax({
                    url: '<?= \yii\helpers\Url::to(['/withdraw/recharge/confirm']) ?>',
                    type: 'POST',
                    data: {
                        data: data,
                        otp: otp,
                    },
                    success: function(dt) {
                        var data = JSON.parse(dt);
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.errors);
                            recharge(data);
                        }
                    }

                });
            }
        }
    }

    function checkrecharge(t) {
        var user_id = $(t).data('user-id');
        var value = $('#recharge-value').val();
        var images = $('input[name^=newimage]').map(function(idx, elem) {
            return $(elem).val();
        }).get();
        if (images.length != 0) {
            if (value) {
                $.ajax({
                    url: '<?= \yii\helpers\Url::to(['/withdraw/recharge/check-recharge']) ?>',
                    type: 'POST',
                    data: {
                        user_id: user_id,
                        value: value,
                        images: images
                    },
                    success: function(dt) {
                        var data = JSON.parse(dt);
                        if (data.success) {
                            recharge(data.data);
                        } else {
                            alert(data.errors);
                        }
                    }

                });
            } else {
                alert('Bạn chưa nhập số V cần nạp.');
            }
        } else {
            alert('Bạn chưa nhập ảnh chứng thực.');
        }
    }
</script>
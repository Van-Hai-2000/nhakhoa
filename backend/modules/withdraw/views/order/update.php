<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$text_opt = $user->getTextOtp();
$this->title = 'Yêu cầu xác nhận nạp V qua chuyển khoản';
$this->params['breadcrumbs'][] = ['label' => 'DS Yêu cầu', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$images = \common\models\order\OrderImages::find()->where(['order_id' => $order->id])->all();
?>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?= $this->title ?>: KEY<span style="color: green;"><?= $order->key ?></span></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <h2>Thông tin chuyển khoản:</h2>
                        <ul>
                            <li>KEY chuyển: <span style="color: green;"><?= $order->key ?></li>
                            <li>Số tiền: <span style="color: green;"><?= formatMoney($order->order_total) ?></li>
                            <li>Quý đổi: <span style="color: green;"><?= $order->getTextV() ?></li>
                            <li>Ngân hàng: <?= $order->getBankname() ?></li>
                        </ul>
                        <div id="w0" class="grid-view">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'product-form',
                                'options' => [
                                    'class' => 'form-horizontal'
                                ]
                            ]);
                            ?>
                            <div class="row">
                                <?php if ($images) foreach ($images as $item) {  ?>
                                    <div class="col-md-6 col-sm-12" style="padding: 10px;">
                                        <img style="max-width: 100%;" src="<?= \common\components\ClaHost::getImageHost() . $item['path'] . $item['name'] ?>">
                                    </div>
                                <?php } ?>
                            </div>
                            <div align="center">
                                <a class="btn btn-primary" onclick="checkrecharge(this)" data-order-id="<?= $order->id ?>">Xác
                                    nhận</a>
                                <a class="btn btn-danger" onclick="cancerorder(this)" data-order-id="<?= $order->id ?>">Hủy yêu cầu</a>
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
        yesPrompt = function(otp, data) {
            if (otp != null) {
                $.ajax({
                    url: '<?= \yii\helpers\Url::to(['/withdraw/order/confirm']) ?>',
                    type: 'POST',
                    data: {
                        data: data,
                        otp: otp,
                    },
                    success: function(dt) {
                        var data = JSON.parse(dt);
                        if (data.success) {
                            window.location.href = '<?= \yii\helpers\Url::to(['/withdraw/order/index']) ?>';
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
        var order_id = $(t).data('order-id');
        var value = $('#recharge-value').val();
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['/withdraw/order/check-recharge']) ?>',
            type: 'POST',
            data: {
                order_id: order_id,
                value: value,
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
    }

    function cancerorder(t) {
        text0 = 'Nhập lý do hủy yêu cầu';
        text1 = 'Không nhận được tiền chuyển khoản';
        promptCS(text0, text1, {
            order_id: $(t).data('order-id')
        });
        yesPrompt = function(conent, data) {
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['/withdraw/order/cancer']) ?>',
                type: 'POST',
                data: {
                    data: data,
                    conent: conent,
                },
                success: function(dt) {
                    var data = JSON.parse(dt);
                    if (data.success) {
                        window.location.href = '<?= \yii\helpers\Url::to(['/withdraw/order/index']) ?>';
                    } else {
                        alert(data.errors);
                        recharge(data);
                    }
                }
            });
        }
    }
</script>
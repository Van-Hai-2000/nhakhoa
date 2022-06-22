<?php

use common\models\order\Order;
use yii\bootstrap\Html;
use common\components\ClaHost;
?>
<div>
    <div class="col-xs-8">
        <?=
            $form->field($model, 'name', [
                'template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}{hint}</div>'
            ])->textInput([
                'class' => 'form-control',
            ])->label($model->getAttributeLabel('name'), [
                'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
            ])
        ?>

        <?=
            $form->field($model, 'phone', [
                'template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}{hint}</div>'
            ])->textInput([
                'class' => 'form-control',
            ])->label($model->getAttributeLabel('phone'), [
                'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
            ])
        ?>

        <?=
            $form->field($model, 'address', [
                'template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}{hint}</div>'
            ])->textInput([
                'class' => 'form-control',
            ])->label($model->getAttributeLabel('address'), [
                'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
            ])
        ?>

        <?=
            $form->field($model, 'payment_status', [
                'template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}{hint}</div>'
            ])->dropDownList(Order::arrayPaymentStatus(), [
                'class' => 'form-control',
                'disabled' => 1,
            ])->label($model->getAttributeLabel('payment_status'), [
                'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
            ])
        ?>

        <?=
            $form->field($model, 'status', [
                'template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}{hint}</div>'
            ])->dropDownList(Order::arrayStatus(), [
                'class' => 'form-control',
            ])->label($model->getAttributeLabel('status'), [
                'class' => 'control-label col-md-3 col-sm-3 col-xs-12'
            ])
        ?>
        <div class="col-xs-3 col-xs-offset-2">
            <?= Html::submitButton('Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="col-xs-4">
        <h2>Note:</h2>
        <?php
        $notes = Order::getOrderNote($model->id);
        if (isset($notes) && $notes) {
            foreach ($notes as $note) {
                $user = backend\models\UserAdmin::findOne($note['user_id']);
        ?>
                <p>- <b><?= $user['username'] ?></b>: <?= $note['note'] ?> (<i><?= date('H:i d/m/Y', $note['created_at']) ?></i>)</p>
        <?php
            }
        }
        ?>

        <textarea class="form-control" name="order_note" id="order_note"></textarea>
        <br />
        <button type="button" onclick="orderNote(<?= $model->id ?>)" class="btn btn-primary">Note</button>
    </div>
    <script type="text/javascript">
        function orderNote(order_id) {
            var note = $('#order_note').val();
            if (order_id && note != '') {
                $.getJSON(
                    '<?= \yii\helpers\Url::to(['/order/order/write-note']) ?>', {
                        order_id: order_id,
                        note: note
                    },
                    function(data) {
                        if (data.code == 200) {
                            alert('Ghi chú thành công!');
                            location.reload(true);
                        }
                    }
                );

            }
        }
    </script>
    <div class="clearfix"></div>
    <p>
        <i class="fa fa-check" style="width: 15px;"></i> Ghi chú: <?= $model->note ?>
    </p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Mã gốc</th>
                <th>Mã sản phẩm</th>
                <th>Giá tiền</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0;
            foreach ($products as $product) {
                // $price_weight = \common\models\product\Product::getPriceWeight($product['price_one_gam']);
                // $detail_code = common\components\ClaLid::generateCodeDetail($product);
                $weight = $product['weight'];
                $price_fee = 0;
                $total_item = $product['price'] * $product['quantity'];
                $total_price += $total_item; ?>
                <tr>
                    <td><?= $product['product_id'] ?></td>
                    <td>
                        <a>
                            <img src="<?= ClaHost::getImageHost(), $product['avatar_path'], 's100_100/', $product['avatar_name'] ?>" />
                        </a>
                    </td>
                    <td><a target="_blank" href="<?= Yii::$app->urlManagerFrontEnd->createUrl(['/site/router-url', 'url' => '/product/product/detail', 'id' => $product['product_id'], 'alias' => $product['alias']]) ?>"><?= $product['name'] ?></a></td>
                    <td><?= $product['code'] ?></td>
                    <td></td>
                    <td><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</td>
                    <td><?= $product['quantity'] ?></td>
                    <td><?= number_format($total_item, 0, ',', '.') ?> VNĐ</td>
                    <td>
                        <?php
                        if ($product['status'] == 0) {
                            echo 'Chưa đặt';
                        } else if ($product['status'] == 1) {
                            echo 'Đã đặt';
                        } else if ($product['status'] == 2) {
                            echo 'Hết hàng';
                        } else if ($product['status'] == 3) {
                            echo '<span style="color: green">Sẵn hàng</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a onclick="return confirm('Bạn có chắc muốn xóa')" href="<?= yii\helpers\Url::to(['/order/order/delete-item', 'id' => $product['id']]) ?>" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
            <?php
            } ?>
            <tr>
                <td colspan="10">
                    <h2>
                        <i class="fa fa-truck" aria-hidden="true"></i>
                        <b><?= Yii::t('app', 'order_history') ?></b>
                    </h2>
                    <p>
                        <?= \common\models\transport\Transport::getName($model['transport_type']) ?> | <?= Yii::t('app', 'order_id') ?> <?= $model['transport_id'] ?>
                    </p>
                    <?php
                    $historys = \common\models\order\OrderShopHistory::getHistory($model['id']);
                    ?>
                    <div class="infor-nguoigiao">
                        <ul>
                            <?php if ($historys) {
                                foreach ($historys as $history) { ?>
                                    <li class="active">
                                        <p>
                                            <span><?= date('h:i:s d-m-Y', $history['time']) ?></span> <?= $history['status'] ?>
                                        </p>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="clearfix"></div>
    <div class="col-xs-6 pull-right">
        <div class="row">
            <?=
                $form->field($model, 'shipfee', [
                    'template' => '{label}<div class="col-md-2 col-sm-2 col-xs-12"><div class="row">{input}{error}{hint}</div></div>'
                ])->textInput([
                    'class' => 'form-control pull-right',
                ])->label($model->getAttributeLabel('shipfee'), [
                    'class' => 'control-label col-md-10 col-sm-10 col-xs-12'
                ])
            ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <p class="pull-right">
        <i class="fa fa-money" style="width: 15px;"></i>
        Tổng tiền đơn hàng:
        <b style="color: red"><?= number_format($total_price, 0, ',', '.') ?> VNĐ</b>
    </p>
    <div class="clearfix"></div>
    <p class="pull-right">
        <i class="fa fa-money" style="width: 15px;"></i>
        Tiền còn lại:
        <?php
        $price_real = $total_price - $model->money_customer_transfer + $model->shipfee;
        ?>
        <b style="color: red"><span class="text-container-order-total"><?= number_format($price_real, 0, ',', '.') ?></span> VNĐ</b>
    </p>
    <div class="clearfix"></div>
    <hr>
    <?php
    $model->order_total = $price_real;
    echo $form->field($model, 'order_total', [
        'template' => '{label}<div class="col-md-2 col-sm-2 col-xs-12"><div class="row">{input}{error}{hint}</div></div>'
    ])->hiddenInput([
        'class' => 'form-control pull-right',
    ])->label(FALSE)
    ?>
</div>

<script type="text/javascript">
    Number.prototype.formatMoney = function(c, d, t) {
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    $(document).ready(function() {
        $('#order-shipfee').on('keyup focus blur', function() {
            var order_total = <?= $total_price ?>;
            var shipfee = $(this).val() ? $(this).val() : 0;
            shipfee = parseInt(shipfee);
            var money_customer_transfer = $('#order-money_customer_transfer').val() ? $('#order-money_customer_transfer').val() : 0;
            var price_real_shipfee = order_total + shipfee;
            console.log(shipfee);
            console.log(price_real_shipfee);
            var price_real = price_real_shipfee - money_customer_transfer;
            var price_real_format = price_real.formatMoney(0, ',', '.');
            $('.text-container-order-total').text(price_real_format);
            $('#order-order_total').val(price_real);
        });
        $('#order-money_customer_transfer').on('keyup focus blur', function() {
            var order_total = <?= $total_price ?>;
            var shipfee = $('#order-shipfee').val() ? $('#order-shipfee').val() : 0;
            shipfee = parseInt(shipfee);
            var money_customer_transfer = $(this).val() ? $(this).val() : 0;
            var price_real_shipfee = order_total + shipfee;
            var price_real = price_real_shipfee - money_customer_transfer;
            var price_real_format = price_real.formatMoney(0, ',', '.');
            $('.text-container-order-total').text(price_real_format);
            $('#order-order_total').val(price_real);
        });
    });
</script>
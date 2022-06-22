<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/8/2021
 * Time: 5:31 PM
 */
$payment_history = \common\models\user\PaymentHistory::find()->where(['medical_record_id' => $model->id])->joinWith(['userAdmin', 'branch'])->orderBy('created_at DESC')->asArray()->all();
?>
<div class="modal fade thanhtoan" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Thanh toán</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <div>
                        <h4 class="modal-title" style="margin-bottom: 15px;font-weight: bold">Thực thu: <span
                                    class="total_money" style="color: red">0 vnđ</span></h4>
                    </div>
                    <div class="prd-add">
                        <div class="row">
                            <input type="hidden" value="" id="payment-id">
                            <div class="col-md-3">
                                <select id="pay_branch" class="form-control" name="pay_branch" required>
                                    <option value="">Chọn chi nhánh</option>
                                    <?php if ($branchs): ?>
                                        <?php foreach ($branchs as $key => $branch): ?>
                                            <option value="<?= $key ?>" <?= isset($user_admin) && $user_admin && $user_admin->branch_id == $key ? 'selected' : '' ?>><?= $branch ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" placeholder="Nhập số tiền thanh toán"
                                       name="pay_money" id="pay_money" required>
                            </div>
                            <div class="col-md-2">
                                <input id="pay_time_create" name="pay-time-create" type="datetime-local"
                                       class="form-control" value="<?= date('Y-m-d\TH:i', time()) ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="type_payment" id="type_payment" class="form-control">
                                    <option value="<?= \common\models\user\PaymentHistory::TYPE_PAYMENT_1 ?>" selected>
                                        Tiền mặt
                                    </option>
                                    <option value="<?= \common\models\user\PaymentHistory::TYPE_PAYMENT_2 ?>">Chuyển
                                        khoản
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select name="type_sale" id="type_sale" class="form-control">
                                    <option value="<?= \common\models\user\PaymentHistory::TYPE_SALE_1 ?>" selected>Giảm
                                        giá theo tiền mặt
                                    </option>
                                    <option value="<?= \common\models\user\PaymentHistory::TYPE_SALE_2 ?>">Giảm giá theo
                                        %
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="number" class="form-control" placeholder="Giá trị giảm giá"
                                       name="pay_sale" id="pay_sale" required>
                            </div>
                            <div class="col-md-12" style="margin-top: 10px">
                                <input class="pay_sale_description form-control" id="pay_sale_description"
                                       placeholder="Lý do giảm giá">
                            </div>
                            <div class="col-md-12" style="margin-top: 10px">
                                <input class="pay_sale_note form-control" id="pay_sale_note" placeholder="Ghi chú">
                            </div>
                        </div>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="clearData('<?= date('Y-m-d\TH:i', time()) ?>')">Xóa dữ liệu form</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="submit_pay()">Xác nhận</button>
                </div>
                <div class="payment_history">
                    <h5 class="modal-title">Lịch sử thanh toán</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Chi nhánh</th>
                            <th>Hinh thức thanh toán</th>
                            <th>Số tiền</th>
                            <th>Giảm giá</th>
                            <th>Thực thu</th>
                            <th>Lý do giảm</th>
                            <th>Người thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody class="body_list_don_thuoc">
                        <?php if (isset($payment_history) && $payment_history): ?>
                            <?php foreach ($payment_history as $value):
                                $type_payment = \common\models\user\PaymentHistory::getTypePayment();
                                ?>
                                <tr>
                                    <td><?= date('d-m-Y H:i:s', $value['created_at']) ?></td>
                                    <td><?= $value['branch']['name'] ?></td>
                                    <td><?= $value['type_payment'] ? $type_payment[$value['type_payment']] : 'Tiền mặt' ?></td>
                                    <td><?= number_format($value['money']) ?></td>
                                    <td><?= $value['type_sale'] == \common\models\user\PaymentHistory::TYPE_SALE_1 ? number_format($value['pay_sale']) . 'đ' : $value['pay_sale'] . '%' ?></td>
                                    <td><?= number_format(\common\models\user\PaymentHistory::getMoney($value['money'], $value['type_sale'], $value['pay_sale'])) ?></td>
                                    <td><?= $value['pay_sale_description'] ?></td>
                                    <td><?= $value['userAdmin']['fullname'] ?></td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           data-url="<?= \yii\helpers\Url::to(['edit-payment', 'id' => $value['id']]) ?>"
                                           title="Sửa" aria-label="Sửa" onclick="edit_payment(<?= $value['id'] ?>,'<?= date('Y-m-d\TH:i', $value['created_at']) ?>')">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </a>
                                        <a href="<?= \yii\helpers\Url::to(['delete-payment', 'id' => $value['id']]) ?>"
                                           title="Xóa" aria-label="Xóa" data-pjax="0"
                                           data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    function clearData(t) {
        $('#payment-id').val('');
        $('#pay_money').val('');
        $('#pay_time_create').val(t);
        $('#type_payment').val(1);
        $('#type_sale').val(1);
        $('#pay_sale').val('');
        $('#pay_sale_description').val('');
        $('#pay_sale_note').val('');
        $('.total_money').text('');
    }

    function edit_payment(payment_id,time_create) {
        var payList = {};
        <?php if (isset($payment_history) && $payment_history): ?>
        payList = <?= json_encode($payment_history) ?>;
        <?php endif; ?>
        $.each( payList, function( key, value ) {
            if(value.id == payment_id){
                console.log(value);
                $('#payment-id').val(payment_id);
                $('#pay_branch').val(value.branch.id);
                $('#pay_money').val(value.money);
                $('#pay_time_create').val(time_create);
                $('#type_payment').val(value.type_payment);
                $('#type_sale').val(value.type_sale);
                $('#pay_sale').val(value.pay_sale);
                $('#pay_sale_description').val(value.pay_sale_description);
                $('#pay_sale_note').val(value.note);
            }
        });
    }
    

    function submit_pay() {
        var branch_id = $('#pay_branch').val();
        var money = $('#pay_money').val();
        var pay_sale = $('#pay_sale').val();
        var pay_sale_description = $('#pay_sale_description').val();
        var type_payment = $('#type_payment').val();
        var type_sale = $('#type_sale').val();
        var note = $('#pay_sale_note').val();
        var pay_time_create = $('#pay_time_create').val();
        var payment_id = $('#payment-id').val();

        if (checkValidate(type_payment, $('#type_payment'), 'Hình thức thanh toán không được bỏ trống') == false || checkValidate(branch_id, $('#pay_branch'), 'Chi nhánh không được bỏ trống') == false || checkValidate(money, $('#pay_money'), 'Số tiền không được bỏ trống') == false) {
            return false
        }

        if (pay_sale) {
            if (checkValidate(type_sale, $('#type_sale'), 'Hình thức giảm giá không được bỏ trống') == false) {
                return false
            }
        }

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['payment', 'id' => $model->id]) ?>',
            type: 'POST',
            data: {
                branch_id: branch_id,
                money: money,
                pay_sale: pay_sale,
                pay_sale_description: pay_sale_description,
                note: note,
                type_sale: type_sale,
                type_payment: type_payment,
                pay_time_create: pay_time_create,
                payment_id: payment_id,
            },
            success: function (data) {
                $(".close_form").trigger('click');
                alert(data)
                setTimeout(function () {
                    window.location.reload()
                }, 2000);
            }
        })
    }

    $("#pay_money").on({
        keyup: function () {
            payMoney($(this));
        },
        blur: function () {
            payMoney($(this));
        }
    });

    $("#pay_sale").on({
        keyup: function () {
            paySale($(this));
        },
        blur: function () {
            paySale($(this));
        }
    });

    $('#type_sale').change(function () {
        paySale($("#pay_sale"));
    });

    function payMoney(input) {
        var pay_sale = $('#pay_sale').val();
        var type_sale = $('#type_sale').val();
        var money = input.val();
        if (pay_sale) {
            var html = money - pay_sale;
            if (type_sale == 2) {
                html = (money * (100 - pay_sale)) / 100;
            }
            $('.total_money').html(formatNumber(html) + ' vnđ');
        } else {
            $('.total_money').html(formatNumber(money) + ' vnđ');
        }
    }

    function paySale(input) {
        var money = $('#pay_money').val();
        var type_sale = $('#type_sale').val();
        var pay_sale = input.val();
        if (type_sale) {
            if (money) {
                var html = money - pay_sale;
                if (type_sale == 2) {
                    html = (money * (100 - pay_sale)) / 100;
                }
                $('.total_money').html(formatNumber(html) + ' vnđ');
            } else {
                $('.total_money').html('0 vnđ');
            }
        }
    }
</script>
<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/8/2021
 * Time: 5:31 PM
 */
use common\components\ClaNhakhoa;
$payment_history = \common\models\hsba\PaymentHistoryV2::find()->where(['medical_record_id' => $id])->joinWith(['userAdmin', 'branch'])->orderBy('created_at DESC')->asArray()->all();
?>
<form id="form_thanh_toan" method="post">
    <div class="form-group">
        <div class="prd-add">
            <form action="" id="form_payment">
                <?php if (\common\components\ClaNhakhoa::check_array($medical_record_item_child)): ?>
                    <?php foreach ($medical_record_item_child as $item):
                        $da_thanh_toan = isset($item['payment']) && ClaNhakhoa::check_array($item['payment']) ? \common\components\ClaNhakhoa::getSum($item['payment'],'money') : 0;
                        ?>
                        <div class="row">
                            <div class="col-md-6" style="display: flex">
                                <button type="button" class="btn btn-success" id="action_<?= $item['id'] ?>" onclick="edit_payment(<?= $item['id'] ?>)">Sửa
                                </button>
                                <input type="text" class="form-control" value="<?= isset($item['product']['name']) && $item['product']['name'] ? $item['product']['name'] : '' ?>" disabled>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="Tổng: <?= number_format($item['money']) ?>" disabled>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="Đã TT: <?= number_format($da_thanh_toan) ?>" disabled>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="Còn lại: <?= number_format($item['money'] - $da_thanh_toan) ?>" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <select id="pay_branch_<?= $item['id'] ?>" class="form-control" disabled>
                                    <?php if ($branchs): ?>
                                        <?php foreach ($branchs as $key => $branch): ?>
                                            <option value="<?= $key ?>" <?= isset($user_admin) && $user_admin && $user_admin->branch_id == $key ? 'selected' : '' ?>><?= $branch ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input id="pay_time_create_<?= $item['id'] ?>" name="" type="datetime-local"
                                       class="form-control" value="<?= date('Y-m-d\TH:i', time()) ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" placeholder="Nhập số tiền thanh toán"
                                       name="" id="pay_money_<?= $item['id'] ?>" disabled>
                            </div>
                            <div class="col-md-2">
                                <select name="" id="type_payment_<?= $item['id'] ?>" class="form-control" disabled>
                                    <option value="<?= \common\models\user\PaymentHistory::TYPE_PAYMENT_1 ?>" selected>
                                        Tiền mặt
                                    </option>
                                    <option value="<?= \common\models\user\PaymentHistory::TYPE_PAYMENT_2 ?>">Chuyển
                                        khoản
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 10px">
                                <input class="pay_sale_note form-control" id="pay_sale_note_<?= $item['id'] ?>" name="" placeholder="Ghi chú" disabled>
                            </div>
                        </div>
                        <div class="help-block"></div>
                        <hr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
</form>

<!--Lịch sử thanh toán-->
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
                           title="Sửa" aria-label="Sửa"
                           onclick="edit_payment(<?= $value['id'] ?>,'<?= date('Y-m-d\TH:i', $value['created_at']) ?>')">
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

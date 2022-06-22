<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/7/2021
 * Time: 4:45 PM
 */

?>

<?php if ($model): ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Tên thủ thuật</th>
            <th>Bác sỹ thực hiện</th>
            <th>Trạng thái</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
            <th style="width: 20%">Hành động</th>
        </tr>
        <tr id="w1-filters" class="filters">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model as $key => $value): ?>
            <tr data-key="54">
                <td><?= $value->product->name ?></td>
                <td><?= $value->userAdmin->fullname ?></td>
                <td>Đã thực hiện</td>
                <td><?= $value->quantity ?></td>
                <td><?= number_format($value->product->price) ?></td>
                <td><?= number_format($value->product->price * $value->quantity) ?></td>
                <td>
                    <?php if (date('d',time()) == date('d',$value->created_at) && date('m',time()) == date('m',$value->created_at)): ?>
                        <?php if ((isset($last_payment) && $last_payment->created_at < $value->created_at) || !$last_payment): ?>
                            <button class="btn btn-danger" onclick="deleteItem(this,<?= $value->id ?>,<?= $value->medical_record_id ?>)">Xóa
                            </button>
                            <button class="btn btn-warning" onclick="cancelItem(this,<?= $value->id ?>,<?= $value->medical_record_id ?>)">Hủy khám
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <button class="pull-right btn btn-primary" data-toggle="modal" data-target=".commission"
                            onclick="load_commission(<?= $value->medical_record_item_id ?>,<?= $value->id ?>,<?= $value->product_id ?>)">Hoa hồng
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/8/2021
 * Time: 2:22 PM
 */
?>
<div class="form-group html_medicine_add" style="display: none">
    <div class="row medicine-add">
        <div class="col-md-6">
            <select class="form-control medicine_add" name="medicine" required>
                <option value=""></option>
                <?php if (isset($medicine) && $medicine): ?>
                    <?php foreach ($medicine as $value): ?>
                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-5">
            <input type="number" class="form-control" name="medicine_quantity" placeholder="Nhập số lượng" required>
        </div>
        <div class="col-md-1">
            <span class="delete-medicine col-md-1">x</span>
        </div>
    </div>
    <div class="help-block"></div>
</div>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Ngày</th>
        <th>Giờ</th>
        <th>Người kê đơn</th>
        <th>Tên thuốc</th>
        <th>Đơn giá</th>
        <th>Số lượng</th>
        <th>Tổng tiền</th>
        <th>Hành động</th>
    </tr>
    </thead>
    <tbody class="body_list_don_thuoc">
    <?php if ($medical_record_item_child): ?>
        <?php foreach ($medical_record_item_child as $value): ?>
            <tr data-key="8">
                <td><?= date('d-m-Y',$value->created_at) ?></td>
                <td><?= date('H:i:s',$value->created_at) ?></td>
                <td><?= isset($value->userAdmin->username) && $value->userAdmin->username ? $value->userAdmin->fullname : '' ?></td>
                <td><?= isset($value->medicine->name) && $value->medicine->name ? $value->medicine->name : '' ?></td>
                <td><?= isset($value->medicine->price) && $value->medicine->price ? number_format($value->medicine->price) : 0 ?></td>
                <td><?= $value->quantity ?></td>
                <td><?= isset($value->medicine) && $value->medicine ? number_format($value->quantity * $value->medicine->price) : 0 ?></td>
                <td>
                    <a href="#" data-url="<?= \yii\helpers\Url::to(['update-medicine']) ?>" title="Sửa" aria-label="Sửa" data-pjax="0" data-toggle="modal" data-target=".medicine_edit" onclick="updateMedicine(<?= $value->id ?>)">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="<?= \yii\helpers\Url::to(['delete-medicine', 'id' => $value->id]) ?>" title="Xóa" aria-label="Xóa" data-pjax="0"
                       data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
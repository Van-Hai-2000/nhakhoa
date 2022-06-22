<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 12/21/2021
 * Time: 11:16 AM
 */


?>
<form id="form_update_commission">
    <input type="hidden" name="item_child_id" value="<?= $item_child_id ?>">
    <input type="hidden" name="item_id" value="<?= $item_id ?>">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Người hưởng thụ</th>
            <th>Hình thức hưởng</th>
            <th>Giá trị</th>
            <th>Xóa</th>
        </tr>
        <tr id="w1-filters" class="filters">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </thead>
        <tbody class="commission_body_child">
        <?php if ($medical_record_item_commission):
            $user_ids = explode(',', $medical_record_item_commission->user_id);
            $values = explode(',', $medical_record_item_commission->value);
            $types = explode(',', $medical_record_item_commission->type);
            ?>
            <?php foreach ($user_ids as $key_us => $user_id): ?>
            <tr class="com-value">
                <td>
                    <select name="commission_team_id[<?= $medical_record_item_commission->product_id ?>][]" id=""
                            class="form-control">
                        <?php foreach ($user_admin as $key => $user): ?>
                            <option value="<?= $key ?>" <?= $key == $user_id ? 'selected' : '' ?>><?= $user ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="commission_team_type[<?= $medical_record_item_commission->product_id ?>][]" id=""
                            class="form-control team_type">
                        <option value="1" <?= 1 == $types[$key_us] ? 'selected' : '' ?>>Theo %</option>
                        <option value="2" <?= 2 == $types[$key_us] ? 'selected' : '' ?>>Tiền mặt</option>
                    </select>
                </td>
                <td>
                    <input name="commission_team_value[<?= $medical_record_item_commission->product_id ?>][]"
                           type="text" class="form-control team_commission" placeholder="nhập giá trị hưởng hoa hồng"
                           value="<?= $values[$key_us] ?>">
                </td>
                <td>
                    <button type="button" class="btn btn-danger delete-commission" onclick="deleteCommission(this)">
                        Xóa
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <a class="btn-add-custom" onclick="add_commission(this,<?= $product_id ?>)"><i class="glyphicon glyphicon-plus"></i>
        Thêm người hưởng</a>
    <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="update_commission(this)">Xác nhận
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
    </div>
</form>

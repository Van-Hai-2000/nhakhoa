<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 9/7/2021
 * Time: 9:22 AM
 */

?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Chỉnh sửa đơn thuốc</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form id="medicine-update-form">
            <div class="modal-body">
                <div class="row prd-add">
                    <input type="hidden" name="id_update" value="<?= $model->id ?>">
                    <div class="col-md-4">
                        <select id="medicine_doctor_id_update" class="form-control" name="medicine_doctor_id_update" required>
                            <option value="">Chọn bác sỹ</option>
                            <?php if ($doctor): ?>
                                <?php foreach ($doctor as $k => $doc): ?>
                                    <option value="<?= $k ?>" <?= $model->doctor_id == $k ? 'selected' : '' ?>><?= $doc ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="help-block"></div>
                    </div>
                    <div class="col-md-4">
                        <select id="medicine_id_update" class="form-control" name="medicine_id_update" required>
                            <option value="">Chọn thuốc</option>
                            <?php if ($medicine): ?>
                                <?php foreach ($medicine as $k => $value): ?>
                                    <option value="<?= $k ?>" <?= $model->medicine_id == $k ? 'selected' : '' ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="help-block"></div>
                    </div>
                    <div class="col-md-4">
                        <input id="medicine_quantity_update" type="number" class="form-control" name="medicine_quantity_update" value="<?= $model->quantity ?>" placeholder="Nhập số lượng">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="help-block"></div>
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="submitMedicine(this)">Xác nhận</button>
            <button type="button" class="btn btn-secondary close_form" data-dismiss="modal">Đóng</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        jQuery("#medicine_id_update").select2({
            placeholder: "Chọn thuốc",
            allowClear: true
        });

        jQuery("#medicine_doctor_id_update").select2({
            placeholder: "Chọn bác sĩ",
            allowClear: true
        });
    });
</script>
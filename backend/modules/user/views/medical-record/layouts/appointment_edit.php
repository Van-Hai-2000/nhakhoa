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
            <h4 class="modal-title" id="exampleModalLabel">Chỉnh sửa lịch hẹn</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row prd-add">
                <div class="col-md-4">
                    <select id="appointment-branch-edit" class="form-control branch" name="branch" required>
                        <option value="">Chọn chi nhánh</option>
                        <?php if ($branchs): ?>
                            <?php foreach ($branchs as $k => $branch): ?>
                                <option value="<?= $k ?>" <?= $model->branch_id == $k ? 'selected' : '' ?>><?= $branch ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="help-block"></div>
                </div>
                <div class="col-md-4">
                    <input type="datetime-local" id="appointment-time-edit" class="form-control" name="time"
                           aria-required="true" aria-invalid="true" value="<?= date('Y-m-d\TH:i',$model->time); ?>">
                    <div class="help-block"></div>
                </div>
                <div class="col-md-4">
                    <select id="appointment-doctor-edit" class="form-control doctor" name="doctor[]" required>
                        <option value="">Chọn bác sỹ</option>
                        <?php if ($doctor): ?>
                            <?php foreach ($doctor as $k => $doc): ?>
                                <option value="<?= $k ?>" <?= $model->doctor_id == $k ? 'selected' : '' ?>><?= $doc ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="help-block"></div>
                </div>
                <div class="col-md-12" style="margin-top: 15px;">
                        <textarea name="note" id="appointment-note-edit" class="form-control" cols="30" rows="4"
                                  placeholder="Ghi chú"><?= $model->description ?></textarea>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="help-block"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="edit_appointment(this)">Xác nhận</button>
            <button type="button" class="btn btn-secondary close_form" data-dismiss="modal">Đóng</button>
        </div>
    </div>
</div>
<script>
    function edit_appointment(t) {
        var check = false;
        var branch = $('#appointment-branch-edit').val();
        var time = $('#appointment-time-edit').val();
        var doctor = $('#appointment-doctor-edit').val();
        var note = $('#appointment-note-edit').val();

        if (!branch) {
            $('#appointment-branch').parent().find('.help-block').html('Chi nhánh không được bỏ trống')
            check = false;
        } else {
            $('#appointment-branch').parent().find('.help-block').empty();
            check = true;
        }

        if (!time) {
            $('#appointment-time').parent().find('.help-block').html('Thời gian không được bỏ trống')
            check = false;
        } else {
            check = true;
            $('#appointment-time').parent().find('.help-block').empty();
        }

        if (!doctor) {
            $('#appointment-doctor').parent().find('.help-block').html('Bác sĩ không được bỏ trống')
            check = false;
        } else {
            check = true;
            $('#appointment-doctor').parent().find('.help-block').empty();
        }

        if (!note) {
            $('#appointment-note').parent().find('.help-block').html('Ghi chú không được bỏ trống')
            check = false;
        } else {
            check = true;
            $('#appointment-note').parent().find('.help-block').empty();
        }
        if (check) {
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['update-appointment']) ?>',
                type: 'POST',
                data: {
                    id: '<?= $model->id ?>',
                    branch_id: branch,
                    time: time,
                    doctor_id: doctor,
                    description: note
                },
                success: function (data) {
                    if (data) {
                        alert('Cập nhật lịch hẹn thành công');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);

                    } else {
                        alert('Thêm lịch hẹn không thành công')
                    }
                }
            })
        }
    }
</script>
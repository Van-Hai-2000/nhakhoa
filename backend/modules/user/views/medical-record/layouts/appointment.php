<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 9/7/2021
 * Time: 9:22 AM
 */

?>

<!--Lịch hẹn-->
<div class="modal fade lichhen" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Lịch hẹn</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Chi nhánh</th>
                        <th>Họ và tên</th>
                        <th>Số điện thoại</th>
                        <th>Bác sỹ thực hiện</th>
                        <th>Ghi chú</th>
                        <th>Đã đến</th>
                        <th class="action-column">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody class="body_appointment">
                    <?php if ($lich_hen): ?>
                        <?php foreach ($lich_hen as $lh): ?>
                            <tr data-key="8">
                                <td><?= date('d-m-Y', $lh->time) ?></td>
                                <td><?= date('H:i:s', $lh->time) ?></td>
                                <td><?= \common\models\branch\Branch::findOne($lh->branch_id)->name; ?></td>
                                <td><?= $lh->name ?></td>
                                <td><?= $lh->phone ?></td>
                                <td><?= isset($lh->userAdmin->username) && $lh->userAdmin->username ? $lh->userAdmin->username : '' ?></td>
                                <td><?= $lh->description ?></td>
                                <td>
                                    <div class="box-checkbox <?= $lh->status == 1 ? 'check' : '' ?>" check="0">
                        <span class="switchery switcherys updateajax"
                              data-link="<?= \yii\helpers\Url::to(['/service/appointment/updatestatus', 'id' => $lh->id]) ?>"><small></small></span>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" data-url="<?= \yii\helpers\Url::to(['edit-appointment', 'id' => $lh->id]) ?>" title="Sửa" aria-label="Sửa" data-pjax="0" data-toggle="modal" data-target=".lichhen_edit" onclick="edit_lichhen(this)">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a href="<?= \yii\helpers\Url::to(['delete-appointment', 'id' => $lh->id]) ?>" title="Xóa" aria-label="Xóa" data-pjax="0"
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
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".lichhen_add"><i
                            class="glyphicon glyphicon-plus"></i> Thêm mới
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<!-- form thêm mới Lịch hẹn-->
<div class="modal fade lichhen_add" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Thêm lịch hẹn</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row prd-add">
                    <div class="col-md-4">
                        <select id="appointment-branch" class="form-control branch" name="branch" required>
                            <option value="">Chọn chi nhánh</option>
                            <?php if ($branchs): ?>
                                <?php foreach ($branchs as $k => $branch): ?>
                                    <option value="<?= $k ?>" <?= isset($user_admin) && $user_admin && $user_admin->branch_id == $k ? 'selected' : '' ?>><?= $branch ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="help-block"></div>
                    </div>
                    <div class="col-md-4">
                        <input type="datetime-local" id="appointment-time" class="form-control" name="time"
                               aria-required="true" aria-invalid="true">
                        <div class="help-block"></div>
                    </div>
                    <div class="col-md-4">
                        <select id="appointment-doctor" class="form-control doctor" name="doctor[]" required>
                            <option value="">Chọn bác sỹ</option>
                            <?php if ($doctor): ?>
                                <?php foreach ($doctor as $k => $doc): ?>
                                    <option value="<?= $k ?>"><?= $doc ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="help-block"></div>
                    </div>
                    <div class="col-md-12" style="margin-top: 15px;">
                        <textarea name="note" id="appointment-note" class="form-control" cols="30" rows="4"
                                  placeholder="Ghi chú"></textarea>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="help-block"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submit_appointment(this)">Xác nhận</button>
                <button type="button" class="btn btn-secondary close_form" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- form chhỉnh sửa Lịch hẹn-->
<div class="modal fade lichhen_edit" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

</div>

<script>
    function submit_appointment(t) {
        var check = false;
        var branch = $('#appointment-branch').val();
        var time = $('#appointment-time').val();
        var doctor = $('#appointment-doctor').val();
        var note = $('#appointment-note').val();
        var medical_record_id = '<?= $model->id ?>';
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
                url: '<?= \yii\helpers\Url::to(['add-appointment']) ?>',
                type: 'POST',
                data: {
                    branch_id: branch,
                    time: time,
                    doctor_id: doctor,
                    description: note,
                    medical_record_id: medical_record_id
                },
                success: function (data) {
                    if (data) {
                        alert('Thêm lịch hẹn thành công');
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

    jQuery(document).on('click', '.box-checkbox', function () {
        if (confirm("<?= Yii::t('app', 'you_sure_change') ?>")) {
            $(this).css('display', 'none');
            setTimeout(function () {
                $('.box-checkbox').css('display', 'block');
            }, 1000);
            var checkbox = $(this).find('.updateajax').first();
            // changeHot(checkbox);
            var label = $(this).find('.switchery').first();
            var ck = $(this);
            var link = checkbox.attr('data-link');

            if (link) {
                jQuery.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: link,
                    data: null,
                    success: function (res) {
                        if (res.code == 200) {
                            checkbox.attr('data-link', res.link);
                            if (!ck.hasClass('check')) {
                                ck.addClass('check');
                            } else {
                                ck.removeClass('check');
                            }
                        }
                        if (res.code == 1) {
                            alert('Không thể chuyển trạng thái khi ngày hẹn chưa đến')
                        }
                    },
                    error: function () {
                    }
                });
            }
        }
        return false;
    });

    function changeHot(_this) {
        var link = _this.attr('data-link');
        if (link) {
            jQuery.ajax({
                type: 'get',
                dataType: 'json',
                url: link,
                data: null,
                success: function (res) {
                    if (res.code == 200) {
                        _this.attr('data-link', res.link);
                    }
                },
                error: function () {
                }
            });
        }
    }

    function edit_lichhen(t) {
        var url = $(t).data('url');
        $.ajax({
            url: url,
            success: function (data) {
                $('.lichhen_edit').empty().append(data)
            }
        })
    }
</script>
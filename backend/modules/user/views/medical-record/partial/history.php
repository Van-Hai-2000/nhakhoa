<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 1/7/2022
 * Time: 4:12 PM
 */

?>
<?php if ($medical_record_history): ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Chi nhánh</th>
            <th>Thủ thuật</th>
            <th>Bác sĩ</th>
            <th>Thời gian</th>
            <th>Ghi chú</th>
            <th>Người tạo</th>
            <th class="action-column">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($medical_record_history as $history): ?>
            <tr data-key="1">
                <td><?= $history->branch->name ?></td>
                <td><?= $history->product->name ?></td>
                <td><?= $history->doctor->fullname ?></td>
                <td><?= date('d-m-Y H:i:s',$history->created_at) ?></td>
                <td><?= $history->note ?></td>
                <td><?= $history->admin_name ?></td>
                <td>
                    <a class="btn btn-warning" data-toggle="modal" data-target=".history" onclick="addHistory(<?= $history->id ?>)">Sửa</a>
                    <a class="btn btn-danger" href="<?= \yii\helpers\Url::to(['/user/medical-record-history/delete','id' => $history->id]) ?>" title="Xóa" aria-label="Xóa" data-pjax="0"
                       data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post">
                        Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<!--Lịch hẹn-->
<div class="modal fade history" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Lịch hẹn</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body history-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submitHistory()">
                    <i class="glyphicon glyphicon-plus"></i> Xác nhận
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<a class="btn-add-custom" data-toggle="modal" data-target=".history" onclick="addHistory('')"><i
            class="glyphicon glyphicon-plus"></i> Thêm
    mới</a>
<script>
    var his_id = '';
    function addHistory(history_id) {
        $.ajax({
            url: '/admin/user/medical-record-history/get-form?id=' +<?= $id ?>,
            type: 'post',
            data: {
                history_id:history_id
            },
            success: function (respone) {
                his_id = history_id;
                $('.history-body').empty().append(respone);
            }
        })
    }

    function submitHistory() {
        $('#form-history').find('.help-block').each(function (index) {
            $(this).text('');
        });
        $.ajax({
            url: '/admin/user/medical-record-history/add-history?id=<?= $id ?>&history_id='+his_id,
            type: 'post',
            data: $('#form-history').serialize(),
            success: function (respone) {
                console.log(respone);
                var data = JSON.parse(respone);
                if (data.success) {
                    alert('Thêm thành công');
                    setTimeout(function () {
                        window.location.reload()
                    }, 2000);
                } else {
                    if (data.errors) {
                        $.each(data.errors, function (key, value) {
                            checkValidate('', $('#medicalrecordhistory-' + key), value[0]);
                        });
                    }
                }
            }
        })
    }


</script>
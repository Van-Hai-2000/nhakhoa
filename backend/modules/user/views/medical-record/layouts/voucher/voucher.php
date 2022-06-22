<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/9/2021
 * Time: 9:25 AM
 */

?>

<div class="modal fade voucher" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Mã giảm giá</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row prd-add factory_input">
                        <input type="hidden" class="form-control" name="voucher_branch_id" id="voucher_branch_id" value="<?= $user_admin->branch_id ?>">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Nhập mã giảm giá" name="voucher" id="voucher" required>
                        </div>
                        <div class="col-md-12">
                            <input id="voucher_time_create" name="voucher-time-create" type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', time()) ?>">
                        </div>
                    </div>
                </div>

                <div class="payment_history">
                    <h5 class="modal-title">Danh sách mã đã áp dụng</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Mã giảm giá</th>
                            <th>Loại giảm giá</th>
                            <th>Giá trị</th>
                            <th>Tổng tiền được giảm</th>
                            <th>Chi nhánh áp dụng</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="body_list_voucher">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="submit_voucher()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<script>
    //load lịch sử đặt xưởng
    function load_voucher() {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['load-voucher', 'id' => $model->id]) ?>',
            type: 'GET',
            data: {},
            success: function (data) {
                $('.body_list_voucher').empty().html(data)
            }
        })
    }

    function submit_voucher() {
        var voucher_branch = $('#voucher_branch_id').val();
        var voucher = $('#voucher').val();
        var voucher_time_create = $('#voucher_time_create').val();
        if (checkValidate(voucher, $('#voucher'), 'Mã giảm giá không được bỏ trống') == false) {
            return false
        }
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['add-voucher', 'id' => $model->id]) ?>',
            type: 'POST',
            data: {
                branch_id: voucher_branch,
                voucher: voucher,
                voucher_time_create: voucher_time_create,
            },
            success: function (data) {
                data = JSON.parse(data);
                if(data.success){
                    $(".close_form").trigger('click');
                    alert(data.message);
                    setTimeout(function () {
                        window.location.reload()
                    }, 2000);
                }else{
                    checkValidate('', $('#voucher'), data.message)
                }

            }
        })
    }
</script>
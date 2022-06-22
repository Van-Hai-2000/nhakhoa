<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/9/2021
 * Time: 9:25 AM
 */

use \common\models\LoaiMau;

$factory = \backend\models\UserAdmin::find()->where(['vai_tro' => \backend\models\UserAdmin::USER_XUONG, 'status' => 1])->all();
?>

<div class="modal fade factory" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Đặt xưởng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row prd-add factory_input">
                        <input type="hidden" value="" id="f_id">
                        <div class="col-md-6">
                            <select id="factory_branch" class="form-control" name="factory_branch" required>
                                <option value="">Chọn chi nhánh</option>
                                <?php if ($branchs): ?>
                                    <?php foreach ($branchs as $key => $branch): ?>
                                        <option value="<?= $key ?>" <?= isset($user_admin) && $user_admin && $user_admin->branch_id == $key ? 'selected' : '' ?>><?= $branch ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select id="factory_id" class="form-control" name="factory_id" required>
                                <option value="">Chọn Xưởng</option>
                                <?php if ($factory): ?>
                                    <?php foreach ($factory as $value): ?>
                                        <option value="<?= $value->id ?>"><?= $value->username ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <select id="factory_device_id" class="form-control" name="factory_device_id" required>
                                <option value="">Chọn loại mẫu</option>
                                <?php foreach (LoaiMau::getLoaimau() as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" placeholder="Số lượng"
                                   name="factory_quantity" id="factory_quantity" required>
                        </div>
                        <div class="col-md-6">
                            <select id="factory_admin_id" class="form-control" name="factory_admin_id" required>
                                <option value=""></option>
                                <?php if (isset($users) && $users): ?>
                                    <?php foreach ($users as $key => $user): ?>
                                        <option value="<?= $key ?>"><?= $user ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" placeholder="Số điện thoại người đặt"
                                   name="factory_phone" id="factory_phone" required>
                        </div>
                        <div class="col-md-6">
                            <input id="factory_time_create" name="factory-time-create" type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', time()) ?>">
                        </div>
                    </div>
                </div>

                <div class="payment_history">
                    <h5 class="modal-title">Danh sách đặt xưởng</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Ngày đặt</th>
                            <th>Ngày trả</th>
                            <th>Xưởng</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Loại mẫu</th>
                            <th>Chi nhánh</th>
                            <th>Mã bảo hành</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody class="body_list_factory">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="clearDataFormFactory('<?= date('Y-m-d\TH:i', time()) ?>')">Xóa dữ liệu form</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="submit_factory()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        jQuery("#factory_admin_id").select2({
            placeholder: "Người đặt",
            minimumResultsForSearch: 1,
        });
    });

    function clearDataFormFactory(t) {
        $('#f_id').val('');
        $('#factory_id').val('');
        $('#factory_device_id').val('');
        $('#factory_quantity').val('');
        $('#factory_admin_id').val('');
        $('#factory_phone').val('');
        $('#factory_time_create').val(t);
        $('#factory_admin_id').trigger('change');
    }

    //load lịch sử đặt xưởng
    function add_factory() {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['load-factory', 'id' => $model->id]) ?>',
            type: 'GET',
            data: {},
            success: function (data) {
                $('.body_list_factory').empty().html(data)
            }
        })
    }

    function submit_factory() {
        var f_id = $('#f_id').val();
        var factory_branch = $('#factory_branch').val();
        var factory_id = $('#factory_id').val();
        var factory_device_id = $('#factory_device_id').val();
        var factory_quantity = $('#factory_quantity').val();
        var factory_admin_id = $('#factory_admin_id').val();
        var factory_phone = $('#factory_phone').val();
        var factory_time_create = $('#factory_time_create').val();
        if (checkValidate(factory_phone, $('#factory_phone'), 'Số điện thoại không được bỏ trống') == false || checkValidate(factory_admin_id, $('#factory_admin_id'), 'Người đặt xưởng không được bỏ trống') == false || checkValidate(factory_branch, $('#factory_branch'), 'Chi nhánh không được bỏ trống') == false || checkValidate(factory_id, $('#factory_id'), 'Xưởng không được bỏ trống') == false || checkValidate(factory_device_id, $('#factory_device_id'), 'Loại mẫu không được bỏ trống') == false || checkValidate(factory_quantity, $('#factory_quantity'), 'Số lượng không được bỏ trống') == false) {
            return false
        }
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['add-factory', 'id' => $model->id]) ?>',
            type: 'POST',
            data: {
                branch_id: factory_branch,
                factory_id: factory_id,
                device_id: factory_device_id,
                quantity: factory_quantity,
                admin_id: factory_admin_id,
                phone: factory_phone,
                factory_time_create: factory_time_create,
                f_id: f_id,
            },
            success: function (data) {
                $(".close_form").trigger('click');
                alert(data);
                setTimeout(function () {
                    window.location.reload()
                }, 2000);
            }
        })
    }
</script>
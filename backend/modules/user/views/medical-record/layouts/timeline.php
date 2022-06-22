<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/7/2021
 * Time: 3:25 PM
 */

use \common\models\user\MedicalRecordItem;

$medical_record_item = MedicalRecordItem::find()->where(['medical_record_id' => $id])->joinWith('branch')->orderBy('created_at DESC')->all();
$first_id = '';
$first_note = '';
$last_payment = \common\models\user\PaymentHistory::find()->where(['medical_record_id' => $id])->orderBy('created_at DESC')->one();
?>

<?php if ($medical_record_item): ?>
    <div class="timeline">
        <div class="col-md-2 body_left">
            <ul class="list_time">
                <?php foreach ($medical_record_item as $key => $value): if ($key == 0) {
                    $medical_record_item_child = \common\models\user\MedicalRecordItemChild::find()->where(['medical_record_item_id' => $value->id])->joinWith(['product', 'userAdmin'])->orderBy('created_at DESC')->all();
                    $first_note = $value->description;
                } ?>
                    <li class="<?= $key == 0 ? 'active' : '' ?> item_time" data-id="<?= $value->id ?>">
                        <a href="javascript:void(0)" data-description="<?= $value->description ?>"
                           onclick="show_item(<?= $value->id ?>,this,<?= $id ?>)"><?= date('d-m-Y', $value->created_at) . ' - ' . $value->branch->name ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-10 body_right">
            <div class="content_body">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Tên thủ thuật</th>
                        <th>Bác sỹ thực hiện</th>
                        <th>Trạng thái</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Giảm giá</th>
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
                    <?php foreach ($medical_record_item_child as $key => $value): ?>
                        <tr data-key="54">
                            <td><?= $value->product->name ?></td>
                            <td><?= $value->userAdmin->fullname ?></td>
                            <td>Đã thực hiện</td>
                            <td><?= $value->quantity ?></td>
                            <td><?= $value->product->price_market ? number_format($value->product->price_market) : number_format($value->product->price) ?></td>
                            <td><?= $value->product->price_market ? number_format($value->product->price_market - $value->product->price) : 0?></td>
                            <td><?= number_format($value->product->price * $value->quantity) ?></td>
                            <td>
                                <?php if (date('d', time()) == date('d', $value->created_at) && date('m', time()) == date('m', $value->created_at)): ?>
                                    <?php if ((isset($last_payment) && $last_payment->created_at < $value->created_at) || !$last_payment): ?>
                                        <button class="btn btn-danger"
                                                onclick="deleteItem(this,<?= $value->id ?>,<?= $id ?>)">Xóa
                                        </button>
                                        <button class="btn btn-warning"
                                                onclick="cancelItem(this,<?= $value->id ?>,<?= $id ?>)">Hủy
                                            khám
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <button class="pull-right btn btn-primary" data-toggle="modal" data-target=".commission"
                                        onclick="load_commission(<?= $value->medical_record_item_id ?>,<?= $value->id ?>,<?= $value->product_id ?>)">
                                    Hoa hồng
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="action">
                <button class="pull-right btn btn-success" data-toggle="modal" data-target=".donthuoc"
                        onclick="load_donthuoc()">Đơn thuốc
                </button>
                <button class="pull-right btn btn-primary" data-toggle="modal" data-target=".hinhanh"
                        onclick="load_image()">Xem hình ảnh
                </button>
                <button class="pull-right btn btn-primary" data-toggle="modal" data-target=".commission"
                        onclick="fix_commission()">
                    Chỉnh sửa
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Đơn thuốc-->
<div class="modal fade donthuoc" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Đơn thuốc</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="donthuoc_body">
                    <textarea name="doctor_note" id="doctor_note" class="form-control" cols="30" rows="10"
                              placeholder="Lời dặn của bác sỹ"><?= $first_note ?></textarea>
                    <div class="help-block"></div>
                    <select name="doctor_medicine" id="doctor_medicine" class="form-control">
                        <option value=""></option>
                        <?php if ($doctor): ?>
                            <?php foreach ($doctor as $k => $doc): ?>
                                <option value="<?= $k ?>"><?= $doc ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input id="medicine_time_create" name="medicine-time-create" type="datetime-local" class="form-control" style="margin-top: 5px"
                           value="<?= date('Y-m-d\TH:i', time()) ?>">
                    <div class="help-block"></div>
                    <div id="box-append-medicine">
                    </div>
                </form>

                <a class="add-select-medicine btn-add-custom"><i class="glyphicon glyphicon-plus"></i> Thêm thuốc</a>
                <div class="help-block"></div>
                <div class="donthuoc_body">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submit_thuoc(this)">Xác nhận
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- form chỉnh sửa đơn thuốc-->
<div class="modal fade medicine_edit" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"></div>

<!--Load hình ảnh bệnh án-->
<div class="modal fade hinhanh" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Hình ảnh</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body hinhanh_body">

            </div>
        </div>
    </div>
</div>

<div class="modal fade zoom_image" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img class="medical_record_image_zoom" src="" alt="">
            </div>
        </div>
    </div>
</div>

<!--Load danh sách hoa hồng-->
<div class="modal fade commission" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Danh sách hoa hồng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body commission_body">

            </div>

        </div>
    </div>
</div>


<script>
    var index_medicine = 1;
    $(document).ready(function () {
        jQuery("#doctor_medicine").select2({
            placeholder: "Chọn bác sỹ kê đơn",
            minimumResultsForSearch: 1,
        });

        $('.add-select-medicine').click(function () {
            $('#box-append-medicine').append('<div id="index-medicine-' + index_medicine + '">' + $('.html_medicine_add').html() + '</div>');

            jQuery("#index-medicine-" + index_medicine).find('.medicine_add').select2({
                placeholder: "Tên thuốc",
                minimumResultsForSearch: 1,
            });
            index_medicine += 1;
        });
        $(document).on('click', '.delete-medicine', function () {
            if (confirm("Xác nhận xóa mục?")) {
                $(this).parents('.medicine-add').parent().remove();
            }
        });
    });

    function fix_commission(id) {
        $.ajax({
            url: '/admin/user/medical-record/load-fix',
            type: 'GET',
            data: {
                id: id,
            },
            success: function (data,model) {
                $('.commission_body').empty().html(data);
                console.log(model);
            }
        })
    }

</script>


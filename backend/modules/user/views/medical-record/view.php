<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecord */

$this->title = 'Khám bệnh - Điều trị';
$this->params['breadcrumbs'][] = ['label' => 'Hồ sơ bệnh án', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$mess = Yii::$app->session->get('total_com');
?>
<div class="form-group field-medicalrecord-status-add">
    <div class="row prd-add">
        <div class="col-md-2">
            <select id="product_category" class="form-control" name="product_category_id[]" required>
                <option value="">Chọn nhóm thủ thuật</option>
                <?php if ($categories): ?>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-4 prd-quantity">
            <select id="medicalrecord-product" class="form-control product_id" name="product_id[]" required>
                <option value="">Chọn thủ thuật</option>
            </select>
            <input type="number" class="form-control" name="quantity[]" placeholder="Số lượng" value="1" required>
        </div>
        <div class="col-md-2">
            <select id="medicalrecord-doctor" class="form-control doctor" name="doctor[]" required>
                <option value="">Chọn bác sĩ</option>
                <?php if ($doctor): ?>
                    <?php foreach ($doctor as $k => $doc): ?>
                        <option value="<?= $k ?>"><?= $doc ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select id="medicalrecord-team" class="form-control team" name="team[]" required multiple>
                <?php if (isset($users) && $users): ?>
                    <?php foreach ($users as $key => $user): ?>
                        <option value="<?= $key ?>"><?= $user ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-1 medicalrecord-action">
            <span class="gender-team col-md-1" onclick="genderTeam(this)">+</span>
            <span class="delete-cat col-md-1">x</span>
        </div>
        <div class="col-md-12" style="margin-top: 5px">
            <input name="prd_note[]" class="form-control" placeholder="Nhập nội dung thủ thuật" />
        </div>
    </div>
    <div class="help-block"></div>
</div>

<!--Thông tin bệnh nhân-->
<div class="medical-record-view">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <button type="button" class="btn btn-success pull-right" data-toggle="modal"
                            data-target=".lieutrinh" onclick="load_plan()"><i class="glyphicon glyphicon-eye-open"></i> Liệu trình điều trị
                    </button>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Mã hồ sơ</label>
                            <h2><?= $model->id ?></h2>
                        </div>
                        <div class="col-md-4">
                            <label for="">Họ và tên</label>
                            <h2><?= $model->username ?></h2>
                        </div>
                        <div class="col-md-4">
                            <label for="">Số điện thoại</label>
                            <h2><?= $model->phone ?></h2>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px">
                        <div class="col-md-4">
                            <label for="">Tổng phải thanh toán</label>
                            <h2 class="money"><?= number_format($model->total_money - $model->sale_money) ?> đ
                            <?php if($model->sale_money): ?>
                                <span class="money-sale"><?= number_format($model->total_money) ?> đ</span>
                            <?php endif; ?>
                            </h2>
                        </div>
                        <div class="col-md-4">
                            <label for="">Tổng đã thanh toán</label>
                            <h2 class="money"><?= number_format($model->money) ?> đ</h2>
                        </div>
                        <div class="col-md-4">
                            <label for="">Còn nợ</label>
                            <h2 class="money"><?= number_format($model->total_money - $model->money - $model->sale_money) ?> đ</h2>
                        </div>
                    </div>
                    <div class="action">
                        <button type="button" class="btn btn-success pull-left" data-toggle="modal"
                                data-target=".khambenh"><i class="glyphicon glyphicon-plus"></i> Khám bệnh
                        </button>
                        <button type="button" class="btn btn-primary pull-left" data-toggle="modal"
                                data-target=".thanhtoan"><i class="glyphicon glyphicon-ok-circle"></i> Thanh toán
                        </button>
                        <button type="button" class="btn btn-warning pull-left" data-toggle="modal"
                                data-target=".lichhen"><i class="glyphicon glyphicon-calendar"></i> Lịch hẹn
                        </button>
                        <button type="button" class="btn btn-danger pull-left" data-toggle="modal"
                                data-target=".factory" onclick="add_factory()"><i class="glyphicon glyphicon-shopping-cart"></i> Đặt xưởng
                        </button>
                        <button type="button" class="btn btn-success pull-left" data-toggle="modal"
                                data-target=".voucher" onclick="load_voucher()"><i class="glyphicon glyphicon-gift"></i> Mã giảm giá
                        </button>
                        <button type="button" class="btn btn-primary pull-left"
                                 onclick="printDiv(<?= $model->id ?>)"><i class="glyphicon glyphicon-print"></i> In hóa đơn
                        </button>
                        <?php if (\backend\modules\auth\components\Helper::checkRoute('/user/medical-record/log')) { ?>
                            <button type="button" class="btn btn-warning pull-right" data-toggle="modal"
                                    data-target=".log" onclick="load_log(<?= $model->id ?>)"><i class="glyphicon glyphicon-film"></i> Xem lịch sử
                            </button>
                        <?php } ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!--Lịch sử log-->
<?= $this->render('layouts/log/log', ['model' => $model]); ?>

<!--Chưa khám-->
<?= $this->render('layouts/chua_kham', ['dataProvider' => $dataProvider,'searchModel' => $searchModel,'model' => $model]); ?>

<!--Đã khám-->
<div class="medical-record-view">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Đã khám</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= $this->render('layouts/timeline', ['id' => $model->id,'doctor' => $doctor]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Khám bệnh-->
<?= $this->render('layouts/khambenh', ['branchs' => $branchs,'user_admin' => $user_admin,'users' => $users,]); ?>

<!--Thanh toán-->
<?= $this->render('layouts/pay', ['branchs' => $branchs,'model' => $model,'user_admin' => $user_admin]); ?>

<!--Lịch hẹn-->
<?= $this->render('layouts/appointment', ['lich_hen' => $lich_hen, 'doctor' => $doctor,'branchs' => $branchs,'model' => $model,'user_admin' => $user_admin]); ?>

<!--Liệu trình điều trị-->
<?= $this->render('layouts/lieutrinh', ['dataProvider' => $dataProvider,'searchModel' => $searchModel,'model' => $model]); ?>

<!--Đặt xưởng-->
<?= $this->render('layouts/factory/factory', ['branchs' => $branchs,'model' => $model,'user_admin' => $user_admin,'users' => $users,]); ?>

<!--Mã giảm giá-->
<?= $this->render('layouts/voucher/voucher', ['model' => $model,'user_admin' => $user_admin]); ?>

<!--In hóa đơn-->
<div id="print_content" style="display: none">
    <?= \common\widgets\print_hoa_don\PrintHoaDon::widget([
        'view' => 'view',
        'medical_record_id' => $model->id
    ]) ?>
</div>
<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

<script type="text/javascript">
    var index = 1;
    $(document).ready(function () {
        var mess = '<?= $mess ?>';
        if(mess){
            alert(mess)
        }

        $('.add-select-cat').click(function () {
            index += 1;
            $('#box-append-cat').append('<div id="index-' + index + '" class="prd-row">' + $('.field-medicalrecord-status-add').html() + '</div>');

            jQuery("#index-" + index).find('.product_id').select2({
                placeholder: "Chọn thủ thuật",
                allowClear: true,
            });

            jQuery("#index-" + index).find('.doctor').select2({
                placeholder: "Chọn bác sỹ",
                allowClear: true,
            });

            jQuery("#index-" + index).find('.team').select2({
                placeholder: "Chọn đội ngũ tham gia",
                allowClear: true,
            });

            jQuery("#index-" + index).find('.branch').select2({
                placeholder: "Chọn chi nhánh",
                allowClear: true,
            });

            jQuery("#index-" + index).find('#product_category').select2({
                placeholder: "Chọn nhóm thủ thuật",
                allowClear: true,
            }).on("change", function (e) {
                var product_category_id = $(this).val();
                var studentSelect = $(this).parents('.prd-add').find('.product_id');
                $.ajax({
                    url: '<?= \yii\helpers\Url::to(['get-product']) ?>',
                    data: {
                        product_category_id: product_category_id
                    },
                    success: function (data) {
                        var res = JSON.parse(data);
                        studentSelect.empty();
                        $.each(res, function (key, value) {
                            var option = new Option(value, key, true, true);
                            studentSelect.append(option).trigger('change');
                        });
                    }
                })
            });
        });
        $(document).on('click', '.delete-cat', function () {
            if (confirm("Xác nhận xóa mục?")) {
                $(this).parents('.prd-add').parent().remove();
            }
        });
    });

    function zoom_image(t) {
        var src = $(t).data('src');
        $('.medical_record_image_zoom').attr('src',src)
    }

    function printDiv(t) {
        window.frames["print_frame"].document.body.innerHTML = document.getElementById('print_content').innerHTML;
        window.frames["print_frame"].window.focus();
        window.frames["print_frame"].window.print();
    }
</script>
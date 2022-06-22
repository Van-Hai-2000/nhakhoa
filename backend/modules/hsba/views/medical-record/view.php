<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Khám bệnh - Điều trị';
$this->params['breadcrumbs'][] = ['label' => 'Hồ sơ bệnh án', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$mess = Yii::$app->session->get('total_com');
?>

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

                    </div>
                    <div class="action">
                        <button type="button" class="btn btn-success pull-left" data-toggle="modal"
                                data-target=".khambenh"><i class="glyphicon glyphicon-plus"></i> Khám bệnh
                        </button>
                        <button type="button" class="btn btn-primary pull-left" data-toggle="modal" onclick="payment('<?= \yii\helpers\Url::to(['load-html-thanh-toan','id' => $model->id]) ?>')"
                                data-target=".thanhtoan"><i class="glyphicon glyphicon-ok-circle"></i> Thanh toán
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

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
                    <?= $this->render('layouts/timeline', ['id' => $model->id,'medical_record_item' => $medical_record_item,'doctor' => $doctor]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Khám bệnh-->
<?= $this->render('layouts/khambenh', ['branchs' => $branchs,'user_admin' => $user_admin,'users' => $users,]); ?>

<!--Thanh toán-->
<div class="modal fade thanhtoan" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Thanh toán</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div id="form_payment" class="modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="submit_pay('<?= \yii\helpers\Url::to(['payment', 'id' => $model->id]) ?>')">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<?php //$this->render('layouts/pay', ['branchs' => $branchs,'model' => $model,'user_admin' => $user_admin]); ?>

<script type="text/javascript">
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
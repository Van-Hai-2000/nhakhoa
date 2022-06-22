<?php

use common\components\ClaHost;
use yii\helpers\Url;

$no_text = "Chưa nhập";
?>
<style>
    table thead th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    table thead th:hover {
        overflow: visible;
    }

    .flex-main {
        display: flex;
        flex: 1;
        justify-content: center;
        align-content: center;
        align-items: center;
        vertical-align: center;
    }

    .avatar-main {
        height: auto;
        width: 100%;
        max-width: 100%;
        min-height: 100px;
    }

    .flex-main .avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .title-box {
        padding-top: 15px;
        padding-bottom: 15px;
        color: #262626;
        font-size: 15px;
        text-transform: uppercase;
        font-weight: bold;
    }

    .info-customer {
        width: 80%;
        justify-content: space-between;
        justify-items: center;
        padding: 8px 0;

    }

    .info-customer-title {
        font-weight: bold;
        width: 40%;
    }

    .info-customer-content {

    }

    .main-form-description {
        margin-top: 30px;
    }

    .main-form-description .text-area-form {
        width: 100%;
        min-height: 100px;
        outline: none;
        resize: none;
        border-radius: 4px;
        border: 1px solid #474747;
    }


</style>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-bars"></i> Hồ sơ bác sĩ: <?= $model['username'] ?> </h2>
        <ul class="nav navbar-right ">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" style="display: block;">
        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
            <li class="nav-item active">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true" aria-expanded="true">Thông tin cơ bản</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                   aria-controls="profile" aria-selected="false">Khám và điều trị</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                   aria-controls="contact" aria-selected="false">Đơn thuốc</a>
            </li>
<!--            <li class="nav-item">-->
<!--                <a class="nav-link" id="libraryimg-tab" data-toggle="tab" href="#libraryimg" role="tab"-->
<!--                   aria-controls="libraryimg" aria-selected="false">Thư viện ảnh</a>-->
<!--            </li>-->
            <li class="nav-item">
                <a class="nav-link" id="datxuong-tab" data-toggle="tab" href="#datxuong" role="tab"
                   aria-controls="datxuong" aria-selected="false">Đặt xưởng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="lichhen-tab" data-toggle="tab" href="#lichhen" role="tab"
                   aria-controls="lichhen" aria-selected="false">Lịch hẹn</a>
            </li>
<!--            <li class="nav-item">-->
<!--                <a class="nav-link" id="thanhtoan-tab" data-toggle="tab" href="#thanhtoan" role="tab"-->
<!--                   aria-controls="thanhtoan" aria-selected="false">Thanh toán</a>-->
<!--            </li>-->
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class=" collapse-link fa fa-align-left"></i>Thông tin cá nhân</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <div><a class="btn btn-primary"
                                        href="<?= Url::to(['/user-admin/update-doctor', 'id' => $model['id']]) ?>"> Sửa
                                        thông tin</a></div>
                            </li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-lg-6 ">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="flex-main avatar-main">
                                        <?php if (isset($model['src']) && $model['src']) { ?>
                                            <img class="avatar "
                                                 src="<?php echo ClaHost::getImageHost() . $model['src'] ?>"
                                                 alt="">
                                        <?php } else { ?>
                                            <img class="avatar"
                                                 src="<?php echo ClaHost::getImageHost() . '/imgs/user_default.png' ?>"
                                                 alt="">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="title-box">THÔNG TIN CƠ BẢN</div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Mã hồ sơ:</div>
                                        <div class="info-customer-content"><?php echo $model['id'] ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Ngày tạo:</div>
                                        <div class="info-customer-content"><?php echo date('d-m-Y', $model['created_at']); ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Tên đăng ký:</div>
                                        <div class="info-customer-content"><?php echo $model['username'] ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Họ và tên:</div>
                                        <div class="info-customer-content"><?php echo $model['fullname'] ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Email:</div>
                                        <div class="info-customer-content"><?php echo $model['email'] ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">số điện thoại:</div>
                                        <div class="info-customer-content"><?php echo $model['phone'] ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">số điện thoại2:</div>
                                        <div class="info-customer-content"><?php echo $model['phone2'] ?></div>
                                    </div>
                                    <div class="title-box">Bằng cấp/Chứng chỉ hành nghề</div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Bằng cấp:</div>
                                        <?php \backend\models\UserAdmin::getDegree();
                                        ?>
                                        <div class="info-customer-content"><?= $model['degree'] ? \backend\models\UserAdmin::getDegree()[$model['degree']] : $no_text ?></div>

                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Đơn vị đào tạo:</div>
                                        <div class="info-customer-content"><?= $model['name_training_unit'] ? $model['name_training_unit'] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Năm tốt nghiệp:</div>
                                        <div class="info-customer-content"><?= $model['graduation_year'] ? $model['graduation_year'] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Chuyên khoa:</div>
                                        <div class="info-customer-content"><?= $model['specialist'] ? $model['specialist'] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Số chúng chỉ:</div>
                                        <div class="info-customer-content"><?= $model['number_of_certificates'] ? $model['number_of_certificates'] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Ngày cấp chứng chỉ :</div>
                                        <div class="info-customer-content"><?= $model['date_range_certificates'] ? date('d-m-Y', $model['date_range_certificates']) : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Nơi cấp chứng chỉ:</div>
                                        <div class="info-customer-content"><?= $model['issued_by_certificates'] ? $model['issued_by_certificates'] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Kinh nghiệm làm việc:</div>
                                        <div class="info-customer-content"><?= $model['work_experience'] ? $model['work_experience'] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Chuyên môn:</div>
                                        <div class="info-customer-content"><?= $model['specialize'] ? \backend\models\UserAdmin::getSpecialize()[$model['specialize']] : $no_text ?></div>
                                    </div>
                                    <div class="d-flex info-customer">
                                        <div class=" info-customer-title">Tình trạng hợp đồng:</div>
                                        <div class="info-customer-content"><?= $model['contract_status'] ? \backend\models\UserAdmin::getContractStatus()[$model['contract_status']] : $no_text ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="title-box">CMND/CCCD</div>
                            <div class="d-flex info-customer">
                                <div class=" info-customer-title">Số CMND/CCCD:</div>
                                <div class="info-customer-content"><?= $model['identification'] ?></div>
                            </div>
                            <div class="d-flex info-customer">
                                <div class=" info-customer-title">Ngày cấp CMND/CCCD:</div>
                                <div class="info-customer-content"><?= date('d-m-Y', $model['date_range_identification']) ?></div>
                            </div>
                            <div class="d-flex info-customer">
                                <div class=" info-customer-title">Nơi cấp:</div>
                                <div class="info-customer-content"><?= $model['issued_by_identification'] ?></div>
                            </div>
                            <div class="col-lg-6">

                                <div class="flex-main avatar-main">
                                    <?php if (isset($model['image_identification_before']) && $model['image_identification_before']) { ?>
                                        <img class="avatar "
                                             src="<?php echo ClaHost::getImageHost() . $model['image_identification_before'] ?>"
                                             alt="">
                                    <?php } else { ?>
                                        <img class="avatar"
                                             src="<?php echo ClaHost::getImageHost() . '/imgs/user_default.png' ?>"
                                             alt="">
                                    <?php } ?>
                                </div>
                                <div class=" " style="text-align: center;font-weight: bold">Ảnh CMND/CCCD mặt trước
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <div class="flex-main avatar-main">
                                    <?php if (isset($model['image_identification_after']) && $model['image_identification_after']) { ?>
                                        <img class="avatar "
                                             src="<?php echo ClaHost::getImageHost() . $model['image_identification_after'] ?>"
                                             alt="">
                                    <?php } else { ?>
                                        <img class="avatar"
                                             src="<?php echo ClaHost::getImageHost() . '/imgs/user_default.png' ?>"
                                             alt="">
                                    <?php } ?>
                                </div>
                                <div style="text-align: center;font-weight: bold">Ảnh CMND/CCCD mặt sau</div>
                            </div>
                            <!--                            <div class="main-form-description">-->
                            <!--                                <div class="title-box">Mô tả khác</div>-->
                            <!--                                <textarea name="" class="text-area-form"></textarea>-->
                            <!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class=" collapse-link fa fa-align-left"></i> Khám và điều trị </h2>
                        <ul class="nav navbar-right ">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse"
                                   data-parent="#accordion" href="#collapseOne" aria-expanded="false"
                                   aria-controls="collapseOne">
                                    <h4 class="panel-title">Hồ sơ bệnh nhân</h4>
                                </a>
                                <div id="collapseOne" class="panel-collapse in collapse" role="tabpanel"
                                     aria-labelledby="headingOne" style="">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="column-title">Mã HSBA</th>
                                                    <th class="column-title">Họ tên</th>
                                                    <th class="column-title">Số điện thoại</th>
                                                    <th class="column-title">Tổng phải thanh toán ( Đã bao gồm VAT
                                                        10% )
                                                    </th>
                                                    <th class="column-title">Tổng đã thanh toán</th>
                                                    <th class="column-title">Còn nợ</th>
                                                    <th class="column-title">Hành động</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!--                                                --><?php
                                                //                                                $i=1;
                                                //                                                if(isset($medical_record) && $medical_record){
                                                //                                                    foreach ($medical_record as $value){
                                                //                                                        ?>
                                                <!--                                                        <tr>-->
                                                <!--                                                            <td> -->
                                                <? //= $i++ ?><!--</td>-->
                                                <!--                                                            <td>-->
                                                <? //= $value['id'] ?><!--</td>-->
                                                <!--                                                            <td>-->
                                                <? //= $value['username'] ?><!--</td>-->
                                                <!--                                                            <td>-->
                                                <? //= $value['phone'] ?><!--</td>-->
                                                <!--                                                            <td>-->
                                                <? //= number_format($value['total_money'] - $value['sale_money']) ?><!--</td>-->
                                                <!--                                                            <td>-->
                                                <? //= number_format($value['money']) ?><!--</td>-->
                                                <!--                                                            <td style="color:#FF0000;">-->
                                                <? //= number_format($value['total_money'] - $value['money'] - $value['sale_money']) ?><!--</td>-->
                                                <!--                                                            <td><a class="btn btn-success" href="-->
                                                <? //= \yii\helpers\Url::to(['/user/medical-record/add', 'id' => $value['id']])?><!--">Xem chi tiết</a> </td>-->
                                                <!--                                                        </tr>-->
                                                <!--                                                    --><?php //}}?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                   data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                   aria-controls="collapseTwo">
                                    <h4 class="panel-title">Liệu trình điều trị</h4>
                                </a>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingTwo" style="">
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="column-title">Nhóm thủ thuật</th>
                                                <th class="column-title"> Thủ thuật</th>
                                                <th class="column-title">Số lần dự kiến</th>
                                                <th class="column-title">Số lần đã thực hiện</th>
                                                <th class="column-title">Đơn giá</th>
                                                <th class="column-title">Tổng tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($medical_record_child) && $medical_record_child) {
                                                foreach ($medical_record_child as $value) {
                                                    ?>
                                                    <tr>
                                                        <td> <?= $i++ ?></td>
                                                        <td><?= $value['productCategory']['name'] ?></td>
                                                        <td><?= $value['product']['name'] ?></td>
                                                        <td><?= $value['quantity'] ?></td>
                                                        <td><?= $value['quantity_use'] ?></td>
                                                        <td><?= number_format($value['money']) ?></td>
                                                        <td>
                                                            <?= number_format($value['money'] * $value['quantity']) ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="headingThree"
                                   data-toggle="collapse" data-parent="#accordion" href="#collapseThree"
                                   aria-expanded="false" aria-controls="collapseThree">
                                    <h4 class="panel-title">Chưa khám</h4>
                                </a>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingThree" style="">
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="column-title">Nhóm thủ thuật</th>
                                                <th class="column-title"> Thủ thuật</th>
                                                <th class="column-title">Số lần còn lại</th>
                                                <th class="column-title">Đơn giá</th>
                                                <th class="column-title">Tổng tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($medical_record_child_no) && $medical_record_child_no) {
                                                foreach ($medical_record_child_no as $value) {
                                                    ?>
                                                    <tr>
                                                        <td> <?= $i++ ?></td>
                                                        <td><?= $value['productCategory']['name'] ?></td>
                                                        <td><?= $value['product']['name'] ?></td>
                                                        <td><?= number_format($value['quantity'] - $value['quantity_use']) ?></td>
                                                        <td><?= number_format($value['money']) ?></td>
                                                        <td>
                                                            <?= number_format($value['money'] * $value['quantity']) ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="headingFour"
                                   data-toggle="collapse" data-parent="#accordion" href="#collapseFour"
                                   aria-expanded="false" aria-controls="collapseFour">
                                    <h4 class="panel-title">Đã khám</h4>
                                </a>
                                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFour" style="">
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="column-title">Địa chỉ</th>
                                                <th class="column-title">Tên thủ thuật</th>
                                                <th class="column-title">Nội dung thực hiện</th>
                                                <th class="column-title">Bác sĩ thực hiện</th>
                                                <th class="column-title">Đội ngũ thực hiện</th>
                                                <th class="column-title">Trạng thái</th>
                                                <th class="column-title">Số lượng</th>
                                                <th class="column-title">Đơn giá</th>
                                                <th class="column-title">Thành tiền</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($medical_record_item_child) && $medical_record_item_child) {
                                                foreach ($medical_record_item_child as $value) {
                                                    $users_com = \common\models\medical_record\MedicalRecordItemCommission::find()->where(['medical_record_item_child_id' => $value['id']])->One();
                                                    $uid = explode(",", $users_com['user_id']);
                                                    ?>
                                                    <tr>
                                                        <td> <?= $i++ ?></td>
                                                        <td><?= $value['branch']['name'] ?></td>
                                                        <td><?= $value['product']['name'] ?></td>
                                                        <td><?= $value['note'] ?></td>
                                                        <td><?= $value['userAdmin']['fullname'] ?></td>
                                                        <td>
                                                            <?php foreach ($uid as $data) : ?>
                                                                <?php $users = \backend\models\UserAdmin::find()->where(['id' => $data])->one(); ?>
                                                                <?= isset($users['fullname']) ? $users['fullname'] . '<br/> ' : '' ?>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td>Đã thực hiện</td>
                                                        <td>
                                                            <?= number_format($value['quantity']) ?>
                                                        </td>
                                                        <td><?= number_format($value['product']['price']) ?></td>
                                                        <td><?= number_format($value['product']['price'] * $value['quantity']) ?></td>
                                                    </tr>
                                                <?php }
                                            } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class=" collapse-link fa fa-align-left"></i> Đơn thuốc</h2>
                        <ul class="nav navbar-right">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><input type="date" id="date_search"><button id="seach_now">Tìm kiếm</button></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th class="column-title">Mã HSBA</th>
                                    <th class="column-title">Ngày</th>
                                    <th class="column-title">Giờ</th>
                                    <th class="column-title">Người kê đơn</th>
                                    <th class="column-title">Tên thuốc</th>
                                    <th class="column-title">Đơn giá</th>
                                    <th class="column-title">Số lượng</th>
                                    <th class="column-title">Tổng tiền</th>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="table_render">
                                <?php if ($medical_record_item_medicine): ?>
                                    <?php foreach ($medical_record_item_medicine as $value): ?>
                                        <tr class="even pointer">
                                            <td>
                                                <?= $value->medical_record_id ?></td>
                                            <td>
                                                <?= isset($value->created_at) && $value->created_at ? date('d-m-Y', $value->created_at) : '' ?></td>
                                            <td>
                                                <?= isset($value->created_at) && $value->created_at ? date('H:i:s', $value->created_at) : "" ?></td>
                                            <td>
                                                <?= isset($value->userAdmin->username) && $value->userAdmin->username ? $value->userAdmin->fullname : '' ?></td>
                                            <td>
                                                <?= isset($value->medicine->name) && $value->medicine->name ? $value->medicine->name : '' ?></td>
                                            <td>
                                                <?= isset($value->medicine->price) && $value->medicine->price ? number_format($value->medicine->price) : "" ?></td>
                                            <td>
                                                <?= isset($value->quantity) && $value->quantity ? $value->quantity : "" ?></td>
                                            <td>
                                                <?= isset($value->medicine) && $value->medicine ? number_format($value->quantity * $value->medicine->price) : "" ?></td>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="tab-pane fade" id="libraryimg" role="tabpanel" aria-labelledby="libraryimg-tab">-->
<!--                <div class="x_panel">-->
<!--                    <div class="x_title">-->
<!--                        <h2>Thư viện ảnh</h2>-->
<!--                        <ul class="nav navbar-right">-->
<!--                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>-->
<!--                        </ul>-->
<!--                        <div class="clearfix"></div>-->
<!--                    </div>-->
<!--                    <div class="x_content">-->
<!--                        <div class="row">-->
<!--                                       --><?php
//                            //                            if(isset($images) && $images){
//                            //
//                            //                                foreach ($images as $key){
//                            //                                    foreach ($key as $value){
//                            //                                        ?>
<!--                                                                <div class="col-md-55">-->
<!--                                                                 <div class="thumbnail">-->
<!--                                                                        <div class="image view view-first" style="height: 100%;">-->
<!--                                                                          <a href="#" onclick="zoom_image(this)" data-toggle="modal" data-target=".zoom_image" data-src="-->
<!--                            --><?// //= \common\components\ClaHost::getImageHost().$value['path'].$value['name'] ?><!--"><img class="medical_record_image" src="-->
<!--                            --><?// //= \common\components\ClaHost::getImageHost().$value['path'].$value['name'] ?><!--" alt=""></a>-->
<!--                                                                            <div class="mask" style="height: 100%">-->
<!--                                                                                  <p>-->
<!--                            --><?// //= $value['name'] ?><!--</p>-->
<!--                                                                            </div>-->
<!--                                                                         </div>-->
<!--                                                                   </div>-->
<!--                                                             </div>-->
<!--                                                         --><?php ////} }}?>
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="tab-pane fade" id="datxuong" role="tabpanel" aria-labelledby="datxuong-tab">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class=" collapse-link fa fa-align-left"></i> Đặt xưởng</h2>
                        <ul class="nav navbar-right">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th class="column-title">Ngày đặt</th>
                                    <th class="column-title">Ngày trả</th>
                                    <th class="column-title">Xưởng</th>
                                    <th class="column-title">Đơn giá</th>
                                    <th class="column-title">Số lượng</th>
                                    <th class="column-title">Loại mẫu</th>
                                    <th class="column-title">chi nhánh</th>
                                    <th class="column-title">Mã bảo hành</th>
                                    <th class="column-title no-link last">
                                        <span class="nobr">Hành động</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($factory): ?>
                                    <?php foreach ($factory as $value): ?>
                                        <tr class="even pointer">
                                            <td>
                                                <?= date('d-m-Y', $value['created_at']) ?></td>
                                            <td>
                                                <?= isset($value['time_return']) && $value['time_return'] ? date('d-m-Y', $value['time_return']) : 'Chờ xưởng xác nhận' ?></td>
                                            <td>
                                                <?= $value['userAdmin']['fullname'] ?></td>
                                            <td>
                                                <?= number_format($value['money']) ?></td>
                                            <td>
                                                <?= $value['quantity'] ?></td>
                                            <td>
                                                <?= isset($value['loaimau']['name']) && $value['loaimau']['name'] ? $value['loaimau']['name'] : '' ?></td>
                                            <td>
                                                <?= $value['branch']['name'] ?></td>
                                            <td>
                                                <?= $value['insurance_code'] ?></td>
                                            <td class="last"><a href="#">Xem chi tiết</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="lichhen" role="tabpanel" aria-labelledby="lichhen-tab">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class=" collapse-link fa fa-align-left"></i> Lịch hẹn</h2>
                        <ul class="nav navbar-right">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline jambo_table bulk_action">
                                <thead>
                                <tr class="headings">
                                    <th class="column-title">Ngày</th>
                                    <th class="column-title">Giờ</th>
                                    <th class="column-title">Chi nhánh</th>
                                    <th class="column-title">Họ và tên</th>
                                    <th class="column-title">Số điện thoại</th>
                                    <th class="column-title">Bác sĩ thực hiện</th>
                                    <th class="column-title">Ghi chú</th>
                                    <th class="column-title">Đã đến</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($lich_hen): ?>
                                    <?php foreach ($lich_hen as $lh): ?>
                                        <tr class="even pointer">
                                            <td>
                                                <?= date('d-m-Y', $lh['time']) ?></td>
                                            <td>
                                                <?= date('H:i:s', $lh['time']) ?></td>
                                            <td>
                                                <?= $lh['branch']['name']; ?></td>
                                            <td>
                                                <?= $lh['user']['username'] ?></td>
                                            <td>
                                                <?= $lh['phone'] ?></td>
                                            <td>
                                                <?= isset($lh['userAdmin']['username']) && $lh['userAdmin']['username'] ? $lh['userAdmin']['username'] : '' ?></td>
                                            <td>
                                                <?= $lh['description'] ?></td>
                                            <td>
                                                <?= $lh['status'] == 1 ? 'Đã đến' : 'Chưa đến' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="tab-pane fade" id="thanhtoan" role="tabpanel" aria-labelledby="thanhtoan-tab">-->
<!--                <div class="x_panel">-->
<!--                    <div class="x_title">-->
<!--                        <h2><i class=" collapse-link fa fa-align-left"></i>Thanh toán</h2>-->
<!--                        <ul class="nav navbar-right">-->
<!--                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                        <div class="clearfix"></div>-->
<!--                    </div>-->
<!--                    <div class="x_content">-->
<!--                        <div class="table-responsive">-->
<!--                            <table class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline jambo_table bulk_action">-->
<!--                                <thead>-->
<!--                                <tr class="headings">-->
<!--                                    <th class="column-title">Thời gian</th>-->
<!--                                    <th class="column-title">Chi nhánh</th>-->
<!--                                    <th class="column-title">Hình thức thanh toán</th>-->
<!--                                    <th class="column-title">Số tiền</th>-->
<!--                                    <th class="column-title">Giảm giá</th>-->
<!--                                    <th class="column-title">Tồng tiền</th>-->
<!--                                    <th class="column-title">Lý do giảm</th>-->
<!--                                    <th class="column-title">Người thanh toán</th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                                              --><?php ////if (isset($payment_history) && $payment_history): ?>
<!--                                                                  --><?php ////foreach ($payment_history as $key):
//                                //                                        foreach ($key as $value):
//                                //                                            $type_payment = \common\models\user\PaymentHistory::getTypePayment();
//                                //                                            ?>
<!--                                                                          <tr>-->
<!--                                                                               <td>-->
<!--                                --><?// //= date('d-m-Y H:i:s', $value['created_at']) ?><!--</td>-->
<!--                                                                               <td>-->
<!--                                --><?// //= $value['branch']['name'] ?><!--</td>-->
<!--                                                                                <td>-->
<!--                                --><?// //= $value['type_payment'] ? $type_payment[$value['type_payment']] : 'Tiền mặt' ?><!--</td>-->
<!--                                                                                <td>-->
<!--                                --><?// //= number_format($value['money']) ?><!--</td>-->
<!--                                                                                <td>-->
<!--                                --><?// //= $value['type_sale'] == \common\models\user\PaymentHistory::TYPE_SALE_1 ? number_format($value['pay_sale']) . 'đ' : $value['pay_sale'] . '%' ?><!--</td>-->
<!--                                                                                --><?php ////$total = \common\models\user\PaymentHistory::getMoney($value['money'], $value['type_sale'], $value['pay_sale']); ?>
<!--                                                                                <td>-->
<!--                                --><?// //= number_format($total) ?><!--</td>-->
<!--                                                                               <td>-->
<!--                                --><?// //= $value['pay_sale_description'] ?><!--</td>-->
<!--                                                                             <td>-->
                             <?// //= $value['userAdmin']['fullname'] ?><!--</td>-->
<!--                                                                          </tr>-->
<!--                                                                       --><?php ////endforeach;?>
<!--                                                                    --><?php ////endforeach; ?>
<!--                                <                                --><?php ////endif; ?>
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var date = $("#date_search");
        var seach_now = $("#seach_now");

        $(seach_now).click(function () {
            $.ajax({
                url:"'"+ <?= \Yii::getAlias('@webroot')+"/ajaxrender'"?>,
                type: 'GET',
                data:
                {
                    'id' :<?= $id ?>,
                    'created_at' : date.val(),
                },
                success: function(data) {
                    $("#table_render").html(data.html);
                }
            });
        })


    })

</script>
<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \common\components\ActiveFormC;


$categories = \common\models\product\ProductCategory ::find() -> all();
$products = \common\models\product\Product ::find() -> all();
$doctors = \backend\models\UserAdmin ::find() -> where(['vai_tro' => 2]) -> all();
$users = \backend\models\UserAdmin ::find() ->where(['status'=>1])-> all();
$branchs = \common\models\branch\Branch ::find() -> all();
$mess = Yii ::$app -> session -> get('total_com');
$count =1 ;
?>

<style>
    .row {
        margin-bottom: 15px;
    }

    .index_ {
        padding: 10px 10px 10px 10px;
        border: 1px solid;
        overflow-x: hidden;
        overflow-y: auto;
        margin-bottom: 20px;
    }
</style>
<div class="modal-body">

    <div id="box-append-cat">
        <?php
        $form = ActiveFormC ::begin1([
            'id' => 'user-form',
            'options' => [
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'title_form' => $this -> title
            ]
        ]);
        ?>
        <div class="row prd-add" style="margin-bottom: 15px">
            <!--                <input type="checkbox">-->
            <div class="col-md-4">
                <select class="form-control product_category_id" name="branch_id[]">
                    <option value="">Chọn chi nhánh</option>
                    <?php if ($info): ?>
                        <?php foreach ($branchs as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= $info -> branch_id == $branch['id'] ? 'selected' : '' ?> ><?= $branch['name'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-4">
                <input name="time-create" type="datetime-local" class="form-control"
                       value="<?= date('Y-m-d\TH:i', $info -> created_at) ?>">
            </div>
        </div>
        <?php foreach ($data as $model)  : $count ++ ?>
        <?php
             $users_com = \common\models\medical_record\MedicalRecordItemCommission::find()->where(['medical_record_item_child_id'=>$model['id']])->One();
             $arr_user_com_id = explode(",",$users_com['user_id']);
            ?>
            <?php
//                    foreach ($arr_user_com_id as $key) {
//                        echo '<pre>';
//                        print_r($key);
//                        echo '</pre>';
//                        die();
//                    }
            ?>

            <div class="index_" id="index_<?= $count ?>">
                <div class="row prd-add">
                    <div class="col-md-3">
                        <select class="form-control product_category_id" name="product_category_id[]">
                            <option value="">Chọn nhóm thủ thuật</option>
                            <?php if ($categories): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option  value="<?= $category['id'] ?>" <?= $model['category_id'] == $category['id'] ? 'selected' : '' ?> ><?= $category['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
<!--                        <select id="medicalrecord-product" class="form-control product_id" name="product_id[]" required>-->
<!--                            <option value="">Chọn thủ thuật</option>-->
<!--                        </select>-->
                        <select  class="medicalrecord-product form-control product_id" name="product_id[]">
                            <option value="">Chọn thủ thuật</option>
                            <?php if ($products): ?>
                                <?php foreach ($products as $product): ?>
                                    <option  value="<?= $product['id'] ?>" <?= $model['product_id'] == $product['id'] ? 'selected' : '' ?>><?= $product['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding: 0;">
                        <input  type="number" class="form-control" name="quantity[]" placeholder="Số lượng" min="1"
                               value="<?= $model['quantity'] ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="money[]" placeholder="Đơn giá"
                               value="<?= floatval($model['money']) ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control doctor_id_default" name="doctor_id[]">
                            <option value="">Chọn bác sĩ</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['id'] ?>" <?= $model['doctor_id'] == $doctor['id'] ? 'selected' : '' ?>><?= $doctor['fullname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                    <div class="col-md-2">
                        <select class="medicalrecord-status form-control branch" name="status[]" required>
                            <option value="0" <?= $model['status'] == 0 ? 'selected' : '' ?>>Chưa thực hiện</option>
                            <option value="1" <?= $model['status'] == 1 ? 'selected' : '' ?>>Đã thực hiện</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control sale_id_default" name="sale_id[]">
                            <option value="">Chọn sale</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="medicalrecord-status form-control care_id_default" name="care_id[]">
                            <option value="">Chọn người chăm sóc lại</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select  class="medicalrecord-team form-control multiselect team" name="team[]" required multiple >
                            <option value="">Đội ngũ tham gia</option>
                            <?php if (isset($users) && $users): ?>
                                <?php foreach ($users as $key ): ?>
                                        <option value="<?= $key['id'] ?>"<?= in_array($key['id'],$arr_user_com_id) ? 'selected' : '' ?>><?= $key['fullname'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-1 medicalrecord-action">
                        <span class="gender-team col-md-1" onclick="genderTeam(this)">+</span>
                        <span class="delete-cat col-md-1">x</span>
                    </div>
                <div class="col-md-12" style="margin-top: 5px">
                    <input name="prd_note[]" class="form-control" placeholder="Nhập nội dung thủ thuật"  value="<?= $model['description'] ?>" />
                </div>
                <div class="help-block"></div>
            </div>
        <?php endforeach; ?>
        <?php ActiveFormC ::end1(['update' => $model['id']]); ?>
    </div>
</div>


<script type="text/javascript">
    $('.product_category_id').change(function () {
        var _this = $(this);
        var id = _this.val();
        var studentSelect = _this.parents('.prd-add').find('.product_id');
        jQuery.ajax({
            url: '<?= \yii\helpers\Url ::to(['get-product']) ?>',
            data: {
                product_category_id: id
            },
            success: function (data) {
                var res = JSON.parse(data);
                $.each(res, function (key, value) {
                    var option = new Option(value, key, true, true);
                    studentSelect.append(option).trigger('change');
                });
            }
        })
    });

    $(".multiselect").select2({
        placeholder: "Chọn đội ngũ tham gia",
        allowClear: true,
    });

    function genderTeam(t) {
        $(t).parents('.prd-add').find(".medicalrecord-team option:selected").each(function () {
            var $this = $(this);
            if ($this.length) {
                var selText = $this.text();
                var selVal = $this.val();
                html += '<div class="row prd-team">\n' +
                    '        <div class="col-md-3">\n' +
                    '            <select class="form-control team_id" name="team_id[' + prd_id + '][]">\n' +
                    '                <option value="' + selVal + '" selected>' + selText + '</option>\n' +
                    '            </select>\n' +
                    '        </div>\n' +
                    '        <div class="col-md-3">\n' +
                    '            <select class="form-control team_type" name="team_type[' + prd_id + '][]">\n' +
                    '                <option value="1" selected>Theo %</option>\n' +
                    '                <option value="2">Tiền mặt</option>\n' +
                    '            </select>\n' +
                    '        </div>\n' +
                    '        <div class="col-md-2">\n' +
                    '            <input type="text" class="form-control team_commission" name="team_commission[' + prd_id + '][]" value="0" placeholder="Nhập giá trị" required>\n' +
                    '        </div>\n' +
                    '    </div>';
            }
        });
        $(t).parents('.prd-add').find('.prd-team').remove();
        $(t).parents('.prd-add').append(html);
    }

    $('#kbsm').on('submit', function (e) {
        var check = true;
        $('.prd-add').each(function () {
            var cm = 0;
            var type1 = false;
            $(this).find('.prd-team').each(function () {
                var $this = $(this);
                if ($this.length) {
                    var selType = $this.find('.team_type').val();
                    var selVal = $this.find('.team_commission').val();
                    if (selType == 1) {
                        cm += parseInt(selVal);
                        type1 = true;
                    }
                }
            });

            if (type1) {
                if (cm != 8) {
                    alert('Tổng % hoa hồng phải bằng 8');
                    check = false;
                }
            }

        });
        if (check == false) {
            return false;
        }
        return true;
    });

</script>

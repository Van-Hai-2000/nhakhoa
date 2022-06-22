<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\UserAdmin;

/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecord */
/* @var $form yii\widgets\ActiveForm */

?>
<style>
    .delete-cat {
        background: red;
        display: inline-block;
        text-align: center;
        color: #fff;
        font-size: 16px;
        padding: 5px 0px;
        cursor: pointer;
        width: 100%;
    }

    .field-medicalrecord-status-add {
        display: none;
    }

    .add-select-cat {
        margin-top: 10px;
        height: 32px;
        border: 1px dashed #CFCFCF;
        text-align: center;
        width: 100%;
        line-height: 32px;
        cursor: pointer;
        display: block;
        font-size: 16px;
        float: right;
        color: #000;
        background: #e5e5e5;
        margin-bottom: 15px;
    }
</style>

<div class="form-group field-medicalrecord-status-add">
    <div class="row prd-add">
        <div class="col-md-5">
            <select id="product_category" class="form-control" name="product_category_id[]">
                <option value="">Chọn nhóm thủ thuật</option>
                <?php if ($categories): ?>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-5">
            <select id="medicalrecord-status" class="form-control product_id" name="product_id[]">
                <option value="">Chọn thủ thuật</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="number" class="form-control" name="quantity[]" placeholder="Số lượng" min="1">
        </div>
        <div class="col-md-1">
            <span class="delete-cat col-md-1">x</span>
        </div>
    </div>
    <div class="help-block"></div>
</div>

<div class="medical-record-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
            <div class="clearfix"></div>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'user_id')->dropDownList(\common\models\user\User::getUser(), [
            'prompt' => 'Chọn bệnh nhân'
        ]) ?>

        <?= $form->field($model, 'branch_id')->dropDownList(\common\models\branch\Branch::getBranch()) ?>

        <?= $form->field($model, 'status')->dropDownList(\common\models\user\MedicalRecord::getStatus()) ?>

        <?= $form->field($model, 'created_at')->textInput(['type' => 'datetime-local']) ?>

        <div class="form-group field-medicalrecord-status">
            <label class="control-label" for="medicalrecord-status">Chọn thủ thuật</label>
            <div class="row">
                <div class="col-md-5">
                    <select id="medicalrecord-product_category_id" class="form-control product_category_default" name="product_category_id[]">
                        <option value="">Chọn nhóm thủ thuật</option>
                        <?php if ($categories): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <select id="medicalrecord-product_id" class="form-control product_default" name="product_id[]">
                        <option value="">Chọn thủ thuật</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="number" class="form-control" name="quantity[]" placeholder="Số lượng" min="1">
                </div>
            </div>
            <div class="help-block"></div>
        </div>

        <div id="box-append-cat">

        </div>

        <a class="add-select-cat">+</a>

        <?= $form->field($model, 'ly_do')->textarea(['rows' => 2]) ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 2]) ?>



        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
</div>

<script type="text/javascript">
    var index = 1;
    $(document).ready(function () {
        jQuery("#medicalrecord-user_id").select2({
            placeholder: "Chọn bệnh nhân",
            allowClear: true,
        });
        jQuery("#medicalrecord-introduce_id").select2({
            placeholder: "Chọn người giới thiệu",
            allowClear: true,
        });
        jQuery(".product_category_default").select2({
            placeholder: "Chọn nhóm thủ thuật",
            allowClear: true,
        }).on("change", function (e) {
            var product_category_id = $(this).val();
            var productidSelect = $('#medicalrecord-product_id');
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['get-product']) ?>',
                data:{
                    product_category_id:product_category_id
                },
                success:function (data) {
                    var res = JSON.parse(data);
                    productidSelect.empty();
                    $.each( res, function( key, value ) {
                        var option = new Option(value, key, true, true);
                        productidSelect.append(option).trigger('change');
                    });
                }
            })
        });
        jQuery(".product_default").select2({
            placeholder: "Chọn thủ thuật",
            allowClear: true,
        });

        $('.add-select-cat').click(function () {
            index += 1;
            $('#box-append-cat').append('<div id="index-'+index+'">' +$('.field-medicalrecord-status-add').html()+ '</div>');

            jQuery("#index-"+index).find('.product_id').select2({
                placeholder: "Chọn thủ thuật",
                allowClear: true,
            });

            jQuery("#index-"+index).find('#product_category').select2({
                placeholder: "Chọn nhóm thủ thuật",
                allowClear: true,
            }).on("change", function (e) {
                var product_category_id = $(this).val();
                var studentSelect = $(this).parents('.prd-add').find('.product_id');
                $.ajax({
                    url: '<?= \yii\helpers\Url::to(['get-product']) ?>',
                    data:{
                        product_category_id:product_category_id
                    },
                    success:function (data) {
                        var res = JSON.parse(data);
                        studentSelect.empty();
                        $.each( res, function( key, value ) {
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
</script>

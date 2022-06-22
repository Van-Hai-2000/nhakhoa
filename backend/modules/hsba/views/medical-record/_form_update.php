<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\UserAdmin;

/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecord */
/* @var $form yii\widgets\ActiveForm */
$index = 1;
if($model->getErrors()){
   $err = $model->getErrors();
   if(isset($err['qty']) && $err['qty']){
        $qty_err = $err['qty'][0];
   }
}
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
            <span class="delete-cat col-md-1" data-is_delete="1">x</span>
        </div>
    </div>
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
            <div class="help-block"><?= isset($qty_err) && $qty_err ? $qty_err : '' ?></div>
        </div>
        <div id="box-append-cat">
            <?php if ($medical_record_child): ?>
                <?php foreach ($medical_record_child as $key => $value): $index++; ?>
                <?php $products = \common\models\product\Product::find()->where(['category_id' => $value->product_category_id])->all(); ?>
                <div id="index-<?= $key+1 ?>">
                    <div class="row prd-add">
                        <div class="col-md-5">
                            <select class="form-control product_category_id" name="product_category_id[]">
                                <option value="">Chọn nhóm thủ thuật</option>
                                <?php if ($categories): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= $value->product_category_id == $category['id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select id="medicalrecord-status" class="form-control product_id_default" name="product_id[]">
                                <option value="">Chọn thủ thuật</option>
                                <?php if ($products): ?>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" <?= $value->product_id == $product['id'] ? 'selected' : '' ?>><?= $product['name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control" name="quantity[]" placeholder="Số lượng" min="1" value="<?= $value->quantity ?>">
                        </div>
                        <div class="col-md-1">
                            <span class="delete-cat col-md-1" data-is_delete="<?= $value->quantity_use ? 0 : 1 ?>">x</span>
                        </div>
                    </div>
                    <div class="help-block"></div>
                </div>

                <?php endforeach; ?>
            <?php endif; ?>

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
    var index = parseInt('<?= $index ?>');
    $(document).ready(function () {
        sl2(jQuery(".product_category_id"));
        jQuery("#medicalrecord-user_id").select2({
            placeholder: "Chọn bệnh nhân",
            allowClear: true,
        });
        jQuery(".product_id_default").select2({
            placeholder: "Chọn thủ thuật",
            allowClear: true,
        });

        $('.add-select-cat').click(function () {
            index += 1;
            $('#box-append-cat').append('<div id="index-' + index + '">' + $('.field-medicalrecord-status-add').html() + '</div>');

            sl2(jQuery("#index-"+index).find('#product_category'));

            jQuery("#index-" + index).find('.product_id').select2({
                placeholder: "Chọn thủ thuật",
                allowClear: true,
            });
        });



        $(document).on('click', '.delete-cat', function () {
            var is_dlt = $(this).data('is_delete');
            if(is_dlt == 0){
                alert('Thủ thuật này đã được sử dụng nên không thể xóa');
            }else{
                if (confirm("Xác nhận xóa mục?")) {
                    $(this).parents('.prd-add').parent().remove();
                }
            }

        });
    });

    function sl2(element) {
        element.select2({
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
    }
</script>

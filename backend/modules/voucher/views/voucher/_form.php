<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\voucher\Voucher;

/* @var $this yii\web\View */
/* @var $model common\models\voucher\Voucher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="voucher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'voucher')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_ids')->dropDownList(Voucher::getProduct(),["multiple" => "multiple"]) ?>

    <?= $form->field($model, 'type')->dropDownList(Voucher::getType()) ?>

    <?= $form->field($model, 'type_value')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'day_start')->textInput(['type' => 'datetime-local']) ?>

    <?= $form->field($model, 'day_end')->textInput(['type' => 'datetime-local']) ?>

    <?= $form->field($model, 'money_start')->textInput() ?>

    <?= $form->field($model, 'money_end')->textInput() ?>

    <?= $form->field($model, 'branch_id')->dropDownList(\common\models\branch\Branch::getBranch(),['prompt' => 'Tất cả']) ?>

    <?= $form->field($model, 'status')->dropDownList(Voucher::getStatus()) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function () {
        $('#voucher-product_ids').select2({

        });
    })
</script>
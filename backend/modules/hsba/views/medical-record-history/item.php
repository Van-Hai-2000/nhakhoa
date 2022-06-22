<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 1/7/2022
 * Time: 4:57 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['id' => 'form-history']); ?>
<?= $form->field($model, 'admin_id')->textInput(['value' => Yii::$app->user->id,'type' => 'hidden'])->label(false) ?>
<?= $form->field($model, 'medical_record_id')->textInput(['value' => $id,'type' => 'hidden'])->label(false) ?>
<?= $form->field($model, 'id')->textInput(['type' => 'hidden'])->label(false) ?>
<div class="col-md-3">
    <?= $form->field($model, 'branch_id')->dropDownList(\common\models\branch\Branch::getBranch(), ['prompt' => 'Chọn chi nhánh']) ?>
</div>
<div class="col-md-3">
    <?= $form->field($model, 'product_id')->dropDownList($products, ['prompt' => 'Chọn thủ thuật']) ?>
</div>
<div class="col-md-3">
    <?= $form->field($model, 'doctor_id')->dropDownList($doctor, ['prompt' => 'Chọn bác sĩ']) ?>
</div>
<div class="col-md-3">
    <?= $form->field($model, 'created_at')->textInput(['type' => 'datetime-local', 'required' => true]) ?>
</div>
<div class="col-md-12">
    <?= $form->field($model, 'note')->textarea() ?>
</div>

<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function () {
        jQuery("#medicalrecordhistory-product_id").select2({
            placeholder: "Chọn thủ thuật",
            allowClear: true,
        });
        jQuery("#medicalrecordhistory-doctor_id").select2({
            placeholder: "Chọn bác sĩ",
            allowClear: true,
        });
    })
</script>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WaitingList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="waiting-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList(\common\models\user\User::getUser(),['prompt' => 'Chọn bệnh nhân']) ?>

    <?= $form->field($model, 'branch_id')->dropDownList(\common\models\branch\Branch::getBranch(),['prompt' => 'Chọn chi nhánh']) ?>

    <?= $form->field($model, 'medical_record_id')->dropDownList($model->id ? \common\models\user\MedicalRecord::getMedicalRecord(['user_id' => $model->user_id]) :[],['prompt' => 'Chọn hồ sơ bệnh án']) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\WaitingList::getStatus()) ?>

    <?= $form->field($model, 'doctor_id')->dropDownList(\backend\models\UserAdmin::getDoctor(),['prompt' => 'Chọn bác sỹ']) ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function () {
        jQuery("#waitinglist-user_id").select2({
            placeholder: "Chọn bệnh nhân",
            allowClear: true,
        }).change(function () {
            var user_id = $(this).val();
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['medical-record']) ?>',
                type: 'GET',
                data: {
                    user_id:user_id
                },
                success: function (data) {
                    $('#waitinglist-medical_record_id').empty().append(data);
                }
            })
        });
    });
</script>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\appointment\Appointment */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="branch-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
            <div class="clearfix"></div>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'branch_id')->dropDownList(\common\models\branch\Branch::getBranch(),['prompt' => 'Chọn chi nhánh']) ?>

        <?= $form->field($model, 'name')->dropDownList(\common\models\user\User::getUserName(),['prompt' => 'Chọn bệnh nhân', 'class' => 'select2 form-control']) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'time')->textInput(['type' => 'datetime-local']) ?>

        <?= $form->field($model, 'doctor_id')->dropDownList(\backend\models\UserAdmin::getDoctor(),['prompt' => 'Chọn bác sỹ']) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'product_category_id')->dropDownList(\common\models\product\ProductCategory::getCategory(),['prompt' => 'Chọn nhóm thủ thuật']) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Xác nhận' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

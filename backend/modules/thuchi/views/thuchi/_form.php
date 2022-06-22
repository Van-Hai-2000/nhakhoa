<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\thuchi\ThuChi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thu-chi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(\common\models\thuchi\ThuChi::getType()) ?>

    <?= $form->field($model, 'category_id')->dropDownList(\common\models\thuchi\ThuChiCategory::getCategory(),['prompt' => 'Chọn danh mục']) ?>

    <?= $form->field($model, 'money')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'branch_id')->dropDownList(\common\models\branch\Branch::getBranch()) ?>

    <?= $form->field($model, 'time')->textInput(['type' => 'datetime-local']) ?>

    <?= $form->field($model, 'type_payment')->dropDownList(\common\models\thuchi\ThuChi::getTypePayment()) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nguoi_chi')->dropDownList(\backend\models\UserAdmin::getUserIntroduce()) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

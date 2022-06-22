<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\medical_record\Factory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="factory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'time_return')->textInput(['type' => 'datetime-local','required' => true]) ?>

    <?= $form->field($model, 'insurance_code')->textInput(['maxlength' => true,'required' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\medical_record\Factory::getStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

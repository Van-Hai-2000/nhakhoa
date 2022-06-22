<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LoaiMau */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loai-mau-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money_market')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($factory,['prompt' => 'Chọn xưởng']) ?>

    <?= $form->field($model, 'status')->dropDownList([1 => 'Hiển thị', 0 => 'Ẩn']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

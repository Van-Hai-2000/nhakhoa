<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\medical_record\MedicalRecordLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="medical-record-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'medical_record_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'branch_id') ?>

    <?= $form->field($model, 'record_before') ?>

    <?php // echo $form->field($model, 'record_after') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'type_id') ?>

    <?php // echo $form->field($model, 'model') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

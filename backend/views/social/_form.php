<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Social */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="social-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin();
            ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= $form->field($model, 'hotline')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'youtube')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'google')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'twitter')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'instagram')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'pinterest')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'linkedin')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'tumblr')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>



</div>

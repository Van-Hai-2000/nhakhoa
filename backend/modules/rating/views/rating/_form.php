<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\rating\Rating */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rating-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'id' => 'news-form',
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'options' => [
                    'class' => 'form-horizontal'
                ]
            ]);
            ?>

            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'rating', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'rating', ['class' => 'form-control', 'type' => 'number']) ?>
                            <?= Html::error($model, 'rating', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'content', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextarea($model, 'content', ['class' => 'form-control', 'rows' => 6]) ?>
                            <?= Html::error($model, 'content', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'status', [1 => 'Hiển thị', 0 => 'Ẩn'], ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

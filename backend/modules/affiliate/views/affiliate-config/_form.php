<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\affiliate\AffiliateConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="affiliate-config-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php
                    $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                        'fieldClass' => 'common\components\MyActiveField'
                    ]);
                    ?>

                    <?= $form->field($model, 'cookie_expire')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'commission_order')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'commission_click')->textInput(['maxlength' => true]) ?>

                    <?=

                    $form->field($model, 'change_phone', [
                        'template' => '{label}{input}{error}{hint}'
                    ])->checkbox([
                        'class' => 'js-switch',
                        'label' => NULL
                    ])->label($model->getAttributeLabel('change_phone'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?= $form->field($model, 'min_price')->textInput(['maxlength' => true]) ?>

                    <?=

                    $form->field($model, 'status', [
                        'template' => '{label}{input}{error}{hint}'
                    ])->checkbox([
                        'class' => 'js-switch',
                        'label' => NULL
                    ])->label($model->getAttributeLabel('status'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

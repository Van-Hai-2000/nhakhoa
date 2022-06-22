<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\recruitment\Benefit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="benefit-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php $form = ActiveForm::begin(); ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'icon_class')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'order')->textInput() ?>

                    <?=
                    $form->field($model, 'isinput')->dropDownList([
                        0 => 'Không', 
                        1 => 'Có'
                    ])
                    ?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

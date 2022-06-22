<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MenuGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-group-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'menu-group-form',
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
                    <?= 
                    $form->field($model, 'name', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tên nhóm'
                    ])->label($model->getAttributeLabel('name'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>
                    
                    <?= 
                    $form->field($model, 'description', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textarea([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập mô tả nhóm',
                        'rows' => 3
                    ])->label($model->getAttributeLabel('description'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

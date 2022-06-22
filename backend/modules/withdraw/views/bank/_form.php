<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\ClaLid;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'user-form',
                        'options' => [
                            'class' => 'form-horizontal',
                            'enctype' => 'multipart/form-data'
                        ]
            ]);
            ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'value' => '']) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'link_facebook')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'user_before')->textInput(['maxlength' => true]) ?>

                    <?=
                        $form->field($model, 'status')->dropDownList([
                            ClaLid::STATUS_ACTIVED => 'Hoạt Động',
                            ClaLid::STATUS_DEACTIVED => 'Khóa',
                        ])
                    ?>
                    
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

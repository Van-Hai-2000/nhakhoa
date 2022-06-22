<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\commission\Commission */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?= Html::encode($this->title) ?></h2>
                <button type="button" class="btn btn-success pull-right" data-toggle="modal"
                        data-target=".lieutrinh"><i class="glyphicon glyphicon-eye-open"></i> Liệu trình điều trị
                </button>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Người hưởng thụ</label>
                        <h2><?= $model->userAdmin->username ?></h2>
                    </div>
                    <div class="col-md-4">
                        <label for="">Tổng tiền hóa đơn</label>
                        <h2><?= number_format($model->total_money) ?></h2>
                    </div>
                </div>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'value')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
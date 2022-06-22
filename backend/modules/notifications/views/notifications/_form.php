<?php

use yii\helpers\Html;
use \common\components\ActiveFormC;
use common\models\notifications\Notifications;

/* @var $this yii\web\View */
/* @var $model common\models\notifications\Notifications */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notifications-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <?php
                    $form = ActiveFormC::begin([
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                        'fieldClass' => 'common\components\MyActiveField'
                    ]);
                    ?>

                    <?= $form->field($model, 'user_id')->dropDownList([-1 => 'Tất cả'] + Notifications::getAllUserSelect()) ?>

                    <script>
                        jQuery(document).ready(function() {
                            jQuery("#notifications-user_id").select2({
                                placeholder: "Chọn loại người nhận",
                                allowClear: true
                            });
                        });
                    </script>

                    <?=
                    $form->field($model, 'type')->dropDownList(Notifications::optionsType(), [
                        'prompt' => '--- Chọn ---'
                    ])
                    ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

                    <?php //$form->fieldB($model, 'updated_at')->textDate(['format' => 'DD-MM-YYYY HH:mm'])->label() ?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveFormC::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
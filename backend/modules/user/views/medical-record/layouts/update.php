<?php

use \common\components\ActiveFormC;
use yii\helpers\Html;
use common\components\ClaHost;
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<div class="news-category-form">
    <div class="">
        <div class="row">
            <?php
            $form = ActiveFormC::begin1([
                'id' => 'user-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                    'title_form' => $this->title
                ]
            ]);
            ?>
            <div class="form-group">
                <?= Html::activeLabel($model, 'doctor_id', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <?= Html::activeDropDownList($model, 'doctor_id', \common\models\user\MedicalRecordItemChild::getName(), ['class' => 'form-control']) ?>
                    <?= Html::error($model, 'doctor_id', ['class' => 'help-block']); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <?= Html::activeDropDownList($model, 'status', \common\models\user\MedicalRecordItemChild::optionStatus(), ['class' => 'form-control']) ?>
                    <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::activeLabel($model, 'quantity', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <?= Html::activeTextInput($model, 'quantity', ['class' => 'form-control', 'placeholder' => 'Nhập số lượng']) ?>
                    <?= Html::error($model, 'quantity', ['class' => 'help-block']); ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::activeLabel($model, 'money', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <?= Html::activeTextInput($model, 'money', ['class' => 'form-control', 'placeholder' => 'Nhập số lượng']) ?>
                    <?= Html::error($model, 'money', ['class' => 'help-block']); ?>
                </div>
            </div>
            
            <?php ActiveFormC::end1(['update' => $model->id]); ?>
        </div>
    </div>

</div>
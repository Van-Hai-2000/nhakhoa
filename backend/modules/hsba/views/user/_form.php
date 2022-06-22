<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */
/* @var $form yii\widgets\ActiveForm */

$us = new \common\models\user\User();
?>

<div class="branch-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType' => true,
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
            <div class="clearfix"></div>
        </div>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'province_id')->dropDownList(\common\models\Province::optionsProvince()) ?>

        <?= $form->field($model, 'district_id')->dropDownList($us->getDistrict($model)) ?>

        <?= $form->field($model, 'ward_id')->dropDownList($us->getWard($model)) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sex')->dropDownList(\common\models\user\User::getSex()) ?>

        <?= $form->field($model, 'birthday')->textInput(['type' => 'date']) ?>

        <?= $form->field($model, 'relationship')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList(\common\models\user\User::getStatus()) ?>

        <?= $form->field($model, 'type_introduce')->dropDownList(\common\models\user\User::getTypeIntroduce(), [
            'prompt' => 'Chọn loại nguồn giới thiệu'
        ]) ?>

        <div class="introduce">
            <?php if (isset($model->type_introduce) && $model->type_introduce == 1): ?>
                <?= $form->field($model, 'introduce_id')->dropDownList(\backend\models\UserAdmin::getUserIntroduce(), [
                    'prompt' => 'Người giới thiệu'
                ]) ?>
            <?php endif; ?>

            <?php if (isset($model->type_introduce) && $model->type_introduce == 2): ?>
                <?= $form->field($model, 'introduce')->radioList(\backend\models\UserAdmin::getIntroduce(), [
                    'class' => 'user-introduce-custom'
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="us-introduce-body">
            <?php if (isset($model->type_introduce) && $model->type_introduce == 2): ?>
                <?= $form->field($model, 'introduce_id')->dropDownList(\backend\models\UserAdmin::getUserByIntroduce($model->introduce), [
                    'prompt' => 'Người giới thiệu',
                ]) ?>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'src', ['class' => 'required control-label']) ?>
            <div class="" style="padding-top: 8px;">
                <?php if ($model->id && $model->src) { ?>
                    <div style="display: block; margin-bottom: 15px;">
                        <img style="max-width: 100px; max-height: 100px"
                             src="<?= \common\components\ClaHost::getImageHost() . $model->src ?>"/>
                    </div>
                <?php } ?>
                <?= Html::activeHiddenInput($model, 'src') ?>
                <?= Html::fileInput('src'); ?>
                <?= Html::error($model, 'src', ['class' => 'help-block']); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Xác nhận' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script>
    $('#user-type_introduce').change(function () {
        var value = $(this).val();
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['introduce']) ?>',
            type: 'get',
            data: {
                type: value
            },
            success: function (response) {
                $('.introduce').empty().append(response);
                $('.us-introduce-body').empty();
            }
        })
    });

    $(document).ready(function () {
        $('input[type=radio]').change(function () {
            var value = $(this).val();
            ab(value);
        });
    });


    function ab(value) {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['us-introduce']) ?>',
            type: 'get',
            data: {
                type: value
            },
            success: function (response) {
                $('.us-introduce-body').empty().append(response);
            }
        })
    }

    jQuery(document).ready(function () {
        $('#user-province_id').on('change', function () {
            jQuery.ajax({
                url: '<?= \yii\helpers\Url::to(['/ajax/get-district']) ?>',
                type: 'GET',
                data: {
                    province_id: this.value,
                },
                success: function (result) {
                    var response = JSON.parse(result);
                    changeDistrict(response);
                }
            });
        });
    });

    function changeDistrict(data) {
        var html_district = '<option value="">Chọn quận/huyện</option>';
        $.each(data, function (key, val) {
            html_district += '<option value="' + key + '">' + val + '</option>';
        });
        jQuery('#user-district_id').empty().append(html_district);

        jQuery('#user-ward_id').empty();

        $('#user-district_id').on('change', function () {
            jQuery.ajax({
                url: '<?= \yii\helpers\Url::to(['/ajax/get-ward']) ?>',
                type: 'GET',
                data: {
                    district_id: this.value,
                },
                success: function (result) {
                    var response = JSON.parse(result);
                    var html_ward = '<option value="">Chọn phường/xã</option>';
                    $.each(response, function (key, val) {
                        html_ward += '<option value="' + key + '">' + val + '</option>';
                    });
                    jQuery('#user-ward_id').empty().append(html_ward);
                }
            });
        });
    }
</script>
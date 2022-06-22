<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/27/2021
 * Time: 8:51 AM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
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

    <input type="file" name="fl" required>
    <div class="clearfix" style="margin-top: 20px"></div>
    <div class="form-group">
        <?= Html::submitButton('Xác nhận', ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\ProvinceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="province-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'latlng') ?>

    <?= $form->field($model, 'avatar_path') ?>

    <?php // echo $form->field($model, 'avatar_name') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'show_in_home')->checkbox() ?>

    <?php // echo $form->field($model, 'region') ?>

    <?php // echo $form->field($model, 'ishot')->checkbox() ?>

    <?php // echo $form->field($model, 'order') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

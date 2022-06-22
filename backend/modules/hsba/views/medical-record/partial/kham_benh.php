<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 11/6/2021
 * Time: 10:45 AM
 */

?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'toan_than')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'benh_chuyen_khoa')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'tom_tat_benh_an')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'chuan_doan')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'mach')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'nhiet_do')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'huyet_ap')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'nhip_tho')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'can_nang')->textInput() ?>
    </div>
</div>

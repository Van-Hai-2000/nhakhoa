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
        <?= $form->field($model, 'truoc_thu_thuat')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'sau_thu_thuat')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'phuong_phap')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'loai_thu_thuat')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'phuong_phap_vo_cam')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'bac_si_thu_thuat')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'thu_thuat_da_lam')->textInput() ?>
    </div>
</div>

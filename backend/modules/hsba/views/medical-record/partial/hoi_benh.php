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
        <?= $form->field($model, 'qua_trinh_benh_ly')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'tien_su_ban_than')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'tien_su_gia_dinh')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'tam_ly_benh_nhan')->textarea() ?>
    </div>
</div>

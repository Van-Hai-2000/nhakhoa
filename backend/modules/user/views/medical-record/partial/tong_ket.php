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
        <?= $form->field($model, 'tinh_trang_ra_vien')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'huong_dieu_tri')->textarea() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'time_end')->textInput(['type' => 'datetime-local']) ?>
    </div>
</div>

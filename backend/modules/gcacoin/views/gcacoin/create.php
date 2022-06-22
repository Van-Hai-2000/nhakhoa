<?php
use yii\bootstrap\ActiveForm;
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Thêm mới cấu hình</h2>
        <div class="clearfix"></div>
    </div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal tasi-form']]) ?>
        <div class="x_content">
           <!--  <div class="form-group">
                <?php //echo $form->field($model,'money') ?>
            </div>
            <div class="form-group">
                <?php //echo $form->field($model,'gcacoin') ?>
            </div> -->
            <div class="form-group">
                <?= $form->field($model,'sale') ?>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="<?= \yii\helpers\Url::to(['index']) ?>" type="submit" class="btn btn-warning">Hủy</a>
        </div>
    <?php ActiveForm::end(); ?>
</div>
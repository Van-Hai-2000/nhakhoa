
<?php
use yii\bootstrap\ActiveForm;
?>
<div class="x_panel">
    <div class="x_title">
        <h2>Xác thực OTP</h2>
        <div class="clearfix"></div>
    </div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal tasi-form']]) ?>
    <div class="x_content">
        <div class="form-group">
            <label for="username" class="text-info">Mã otp:</label><br>
            <input type="text" name="otp" id="username" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Xác nhận</button>
        <a href="<?= \yii\helpers\Url::to(Yii::$app->homeUrl) ?>" type="submit" class="btn btn-warning">Hủy</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>


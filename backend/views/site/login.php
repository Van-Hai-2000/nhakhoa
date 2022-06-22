<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Bảng quản trị';
?>

<?php ActiveForm::begin(['id' => 'login-form']); ?>
<h1><?= $this->title ?></h1>
<div>
    <?= Html::activeTextInput($model, 'username', ['autofocus' => true, 'placeholder' => 'Tên đăng nhập', 'class' => 'form-control']); ?>
    <?= Html::error($model, 'username', ['class' => 'help-block']) ?>
</div>
<div>
    <?= Html::activePasswordInput($model, 'password', ['placeholder' => 'Mật khẩu', 'class' => 'form-control']); ?>
    <?= Html::error($model, 'password', ['class' => 'help-block']) ?>
</div>
<div>
    <?= Html::activeCheckbox($model, 'rememberMe') ?>
    <button class="btn btn-default submit" style="margin-left: 25px;">Đăng nhập</button>
</div>

<div class="clearfix"></div>

<div class="separator">
    <div class="clearfix"></div>
    <br />
    <div>
        <h1><i class="fa fa-paw"></i> OCOP</h1>
        <p>©2018 All Rights Reserved. OCOP! is a Bootstrap 3 template. Privacy and Terms</p>
    </div>
</div>
<?php ActiveForm::end(); ?>

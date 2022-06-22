<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Xin chào <?= Html::encode($user->username) ?>,</p>

    <p>Thực hiện theo liên kết dưới đây để đặt lại mật khẩu của bạn:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

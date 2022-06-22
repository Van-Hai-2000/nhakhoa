<?php

use yii\widgets\ActiveForm;

?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tài khoản</th>
            <th>Số Vr</th>
            <th>Quy đổi tiền mặt</th>
            <th>Số tài khoản</th>
            <th>Chủ thẻ</th>
            <th>Ngân hàng</th>
        </tr>
    </thead>
    <tbody>
        <tr data-key="2">
            <td><?= $username ?></td>
            <td><?= formatMoney($model->value)  ?></td>
            <td><?= formatMoney(\common\models\gcacoin\Gcacoin::getMoneyToCoin($model->value)) ?></td>
            <td><?= $bank->number ?></td>
            <td><?= $bank->name ?></td>
            <td><?= $bank_name ?></td>
        </tr>
    </tbody>
</table>
<?php
$form = ActiveForm::begin([
    'id' => 'product-form',
    'options' => [
        'class' => 'form-horizontal'
    ]
]);
?>
<?= $this->render('partial/image', ['form' => $form, 'model' => $model, 'images' => $images]); ?>
<div align="center">
    <button class="btn btn-primary" type="submit">Xác nhận</button>
    <a href="<?= \yii\helpers\Url::to(['withdraw/index']) ?>" class="btn btn-danger">Hủy</a>
</div>
<?php ActiveForm::end(); ?>
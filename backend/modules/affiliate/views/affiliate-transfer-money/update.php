<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\affiliate\AffiliateTransferMoney */

$this->title = 'Cập nhật yêu cầu: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách yêu cầu rút tiền', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="affiliate-transfer-money-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

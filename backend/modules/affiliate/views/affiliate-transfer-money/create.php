<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\affiliate\AffiliateTransferMoney */

$this->title = 'Create Affiliate Transfer Money';
$this->params['breadcrumbs'][] = ['label' => 'Affiliate Transfer Moneys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="affiliate-transfer-money-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

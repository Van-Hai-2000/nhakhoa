<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\affiliate\AffiliateConfig */

$this->title = 'Cấu hình Affiliate';
$this->params['breadcrumbs'][] = ['label' => 'Cấu hình Affiliate', 'url' => ['index']];
?>
<div class="affiliate-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

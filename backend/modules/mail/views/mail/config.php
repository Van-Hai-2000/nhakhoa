<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\rating\Rating */

$this->title = 'Cấu hình mail';
?>
<div class="rating-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

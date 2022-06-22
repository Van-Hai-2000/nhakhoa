<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\notifications\Notifications */

$this->title = 'Tạo thông báo';
$this->params['breadcrumbs'][] = ['label' => 'Thông báo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

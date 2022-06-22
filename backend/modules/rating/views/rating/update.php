<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\rating\Rating */

$this->title = Yii::t('app', 'rate').': ID' . $model->object_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'rate'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->object_id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rating-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FilterChar */

$this->title =  Yii::t('app', 'update') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'filter_char_mangament'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="filter-char-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

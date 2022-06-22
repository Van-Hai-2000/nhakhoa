<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\FilterChar */

$this->title = Yii::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'filter_char_mangament'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filter-char-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

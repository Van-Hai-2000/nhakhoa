<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\rating\Rating */

$this->title = 'Create Rating';
$this->params['breadcrumbs'][] = ['label' => 'Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\commission\Commission */

$this->title = 'Create Commission';
$this->params['breadcrumbs'][] = ['label' => 'Commissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="commission-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\medical_record\Factory */

$this->title = 'Create Factory';
$this->params['breadcrumbs'][] = ['label' => 'Factories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="factory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

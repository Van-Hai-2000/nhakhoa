<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecordChild */

$this->title = 'Create Medical Record Child';
$this->params['breadcrumbs'][] = ['label' => 'Medical Record Children', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-record-child-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

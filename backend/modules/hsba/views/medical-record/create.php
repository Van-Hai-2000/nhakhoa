<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecord */

$this->title = 'Tạo mới';
$this->params['breadcrumbs'][] = ['label' => 'Hồ sơ bệnh án', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-record-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>

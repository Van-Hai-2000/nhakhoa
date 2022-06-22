<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\branch\Branch */

$this->title = 'Thêm chi nhánh';
$this->params['breadcrumbs'][] = ['label' => 'Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

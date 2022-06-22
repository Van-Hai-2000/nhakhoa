<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Skill */

$this->title = 'Tạo mới kỹ năng';
$this->params['breadcrumbs'][] = ['label' => 'Quản lí kỹ năng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skill-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

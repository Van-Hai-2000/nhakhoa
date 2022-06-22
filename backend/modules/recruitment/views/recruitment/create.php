<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Recruitment */

$this->title = 'Đăng tin tuyển dụng';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý tuyển dụng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recruitment-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'model_info' => $model_info
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\user\User */

$this->title = 'Thêm bệnh nhân';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách bệnh nhân', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

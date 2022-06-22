<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = "Cấu hình V";
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="user-update">
    <?=
        $this->render('_formc', [
            'model' => $model,
        ])
    ?>
</div>
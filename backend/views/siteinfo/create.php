<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Siteinfo */

$this->title = 'Create Siteinfo';
$this->params['breadcrumbs'][] = ['label' => 'Siteinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siteinfo-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>

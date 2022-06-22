<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\product\MapPriceFormula */

$this->title = Yii::t('app','update');
$this->params['breadcrumbs'][] = ['label' =>  Yii::t('app','promotion_management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="map-price-formula-update">

    <?= $this->render('_form', [
        'model' => $model,
        'products' => $products, 
    ]) ?>

</div>

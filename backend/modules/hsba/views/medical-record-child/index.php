<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\user\search\MedicalRecordChildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Medical Record Children';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-record-child-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Medical Record Child', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'medical_record_id',
            'product_category_id',
            'product_id',
            // 'quantity',
            // 'quantity_use',
            // 'money',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

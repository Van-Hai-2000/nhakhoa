<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\medical_record\MedicalRecordLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Medical Record Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-record-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Medical Record Log', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'medical_record_id',
            'user_id',
            'branch_id',
            'record_before:ntext',
            // 'record_after:ntext',
            // 'action',
            // 'type',
            // 'type_id',
            // 'model',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\log\ActiverecordlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Activerecordlogs';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'created_at',
            'label' => 'Vai trò',
            'value' => function ($model) {
                return isset($model->userAdmin) && $model->userAdmin ? \backend\models\UserAdmin::arrayType()[$model->userAdmin->vai_tro] : 'Người dùng';
            },
        ],
        [
            'attribute' => 'user_id',
            'value' => function ($model) {
                return isset($model->userAdmin) && $model->userAdmin ? $model->userAdmin->fullname . ' - ' .$model->userAdmin->username : 'Trên APP';
            },
        ],
        'description',
        [
            'attribute' => 'action',
            'content' => function ($model) {
                return $model->action;
            },
            'filter' => Html::activeDropDownList($searchModel, 'action', \common\components\ClaLog::getAction(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'selects')])
        ],
        [
            'attribute' => 'model',
            'value' => function ($model) {
                $modelName = isset(\common\components\ClaLog::getTableName()[$model->model]) && \common\components\ClaLog::getTableName()[$model->model] ? \common\components\ClaLog::getTableName()[$model->model] : $model->model;
                return $modelName;
            }
        ],
        'idModel',
        // 'record_before:ntext',
        // 'record_after:ntext',
        // 'created_at',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

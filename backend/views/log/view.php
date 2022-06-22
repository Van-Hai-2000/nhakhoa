<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\log\Activerecordlog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Activerecordlogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activerecordlog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'description',
            'action',
            'model',
            'idModel',
            [
                'attribute' => 'record_before',
                'format' => 'html',
                'value' => function ($model) {
                    $content = \common\components\ClaNhakhoa::getContentLog($model->record_before);
                    return $content;
                }
            ],
            [
                'attribute' => 'record_after',
                'format' => 'html',
                'value' => function ($model) {
                    $content = \common\components\ClaNhakhoa::getContentLog($model->record_after);
                    return $content;
                }
            ],
            'user_id',
            'created_at',
        ],
    ]) ?>

</div>

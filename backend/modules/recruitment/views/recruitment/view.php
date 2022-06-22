<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\recruitment\Recruitment */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Recruitments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recruitment-view">

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
            'user_id',
            'title',
            'alias',
            'level',
            'category_id',
            'typeofworks',
            'locations',
            'knowledge',
            'skills',
            'quantity',
            'priority',
            'salaryrange',
            'currency',
            'salary_min',
            'salary_max',
            'experience',
            'expiration_date',
            'publicdate',
            'created_at',
            'updated_at',
            'status',
            'viewed',
            'avatar_path',
            'avatar_name',
            'ishot',
        ],
    ]) ?>

</div>

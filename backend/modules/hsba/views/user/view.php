<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

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
            'username',
            'phone',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? 'Kích hoạt' : 'Khóa';
                }
            ],
            'address',
            [
                'attribute' => 'sex',
                'value' => function ($model) {
                    return \common\models\user\User::getSex()[$model->sex];
                }
            ],
            [
                'attribute' => 'birthday',
                'value' => function ($model) {
                    return date('d-m-Y', $model->birthday);
                }
            ],
            'relationship',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d-m-Y', $model->created_at);
                }
            ],
        ],
    ]) ?>

</div>

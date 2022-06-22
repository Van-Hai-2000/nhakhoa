<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\appointment\Appointment */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách lịch hẹn', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appointment-view">

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
            [
                'attribute' => 'doctor_id',
                'value' => function ($model) {
                    $doctor = \backend\models\UserAdmin::findOne($model->doctor_id);

                    return $doctor ? $doctor->fullname : 'Không có';
                }
            ],
            [
                'attribute' => 'time',
                'value' => function ($model) {
                    return date('d-m-Y H:i:s', $model->time);
                }
            ],
            'description:ntext',
            'name',
            'phone',
            'medical_record_id',
            [
                'attribute' => 'product_category_id',
                'value' => function ($model) {
                    $category = \common\models\product\ProductCategory::findOne($model->product_category_id);
                    return $category ? $category->name : '';
                }
            ],
            [
                'attribute' => 'branch_id',
                'value' => function ($model) {
                    return \common\models\branch\Branch::findOne($model->branch_id)->name;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d-m-Y', $model->created_at);
                }
            ],
        ],
    ]) ?>

</div>

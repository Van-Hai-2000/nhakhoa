<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\medical_record\Factory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách đặt xưởng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="factory-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'medical_record_id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->username;
                }
            ],
            [
                'attribute' => 'factory_id',
                'value' => function ($model) {
                    return $model->userAdmin->fullname;
                }
            ],
            [
                'attribute' => 'money',
                'value' => function ($model) {
                    return number_format($model->money);
                }
            ],
            'quantity',
            [
                'attribute' => 'device_id',
                'value' => function ($model) {
                    return $model->loaimau->name;
                }
            ],
            [
                'attribute' => 'branch_id',
                'value' => function ($model) {
                    return $model->branch->name;
                }
            ],
            [
                'attribute' => 'admin_id',
                'value' => function ($model) {
                    $userAdmin = \backend\models\UserAdmin::findOne($model->admin_id);
                    return $userAdmin->fullname;
                }
            ],
            [
                'attribute' => 'admin_id',
                'label' => 'Người gửi yêu cầu',
                'value' => function ($model) {
                    $userAdmin = \backend\models\UserAdmin::findOne($model->user_action_id);
                    return $userAdmin ? $userAdmin->fullname : '';
                }
            ],
            'phone',
            'insurance_code',
            'insurance_code_private',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d-m-Y H:i:s',$model->created_at);
                }
            ],
            [
                'attribute' => 'time_return',
                'value' => function ($model) {
                    return $model->time_return ? date('d-m-Y H:i:s',$model->time_return) : 'Chờ xưởng xác nhận';
                }
            ]
        ],
    ]) ?>

</div>

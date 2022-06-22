<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\thuchi\ThuChi */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách thu chi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thu-chi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return \common\models\thuchi\ThuChi::getType()[$model->type];
                }
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    $category = \common\models\thuchi\ThuChiCategory::findOne($model->category_id);
                    return $category ? $category->name : '';
                }
            ],
            [
                'attribute' => 'branch_id',
                'value' => function ($model) {
                    $branch = \common\models\branch\Branch::findOne($model->branch_id);
                    return $branch ? $branch->name : '';
                }
            ],
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    $user = \common\models\user\User::findOne($model->user_id);
                    return $user ? $user->username : '';
                }
            ],
            [
                'attribute' => 'money',
                'label' => 'Hình thức thanh toán',
                'value' => function ($model) {
                    return isset($model->payment) && $model->payment->type_payment ? \common\models\user\PaymentHistory::getTypePayment()[$model->payment->type_payment] : 'Tiền mặt';
                }
            ],
            [
                'attribute' => 'money',
                'label' => 'Tổng tiền phải thanh toán',
                'value' => function ($model) {
                    $money = $model->money;
                    if(isset($model->medical_record_id) && $model->medical_record_id){
                        $medical = \common\models\user\MedicalRecord::findOne($model->medical_record_id);
                        $money = $medical->total_money;
                    }
                    return number_format($money);
                }
            ],
            [
                'attribute' => 'money',
                'label' => 'Thực thu',
                'value' => function ($model) {
                    return number_format($model->money);
                }
            ],
            [
                'attribute' => 'money',
                'label' => 'Số tiền còn lại',
                'value' => function ($model) {
                    $money = 0;
                    if(isset($model->medical_record_id) && $model->medical_record_id){
                        $medical = \common\models\user\MedicalRecord::findOne($model->medical_record_id);
                        $money = $medical->total_money - $medical->money;
                    }
                    return number_format($money);
                }
            ],
            [
                'attribute' => 'money',
                'label' => 'Giảm giá',
                'value' => function ($model) {
                    return isset($model->payment) && $model->payment->pay_sale ? ($model->payment->type_sale == \common\models\user\PaymentHistory::TYPE_SALE_1 ? number_format($model->payment->pay_sale) . 'đ' : $model->payment->pay_sale . '%') : '';
                }
            ],
            [
                'attribute' => 'time',
                'value' => function ($model) {
                    return date('d-m-Y', $model->time);
                }
            ],
            'note:ntext',
            [
                'attribute' => 'admin_id',
                'value' => function ($model) {
                    $admin = \backend\models\UserAdmin::findOne($model->admin_id);
                    return $admin ? $admin->fullname : '';
                }
            ],
            [
                'attribute' => 'nguoi_chi',
                'value' => function ($model) {
                    $nguoi_chi = \backend\models\UserAdmin::findOne($model->nguoi_chi);
                    return $nguoi_chi ? $nguoi_chi->fullname : '';
                }
            ],

        ],
    ]) ?>

</div>

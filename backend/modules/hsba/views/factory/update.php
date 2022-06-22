<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\medical_record\Factory */

$this->title = 'Cập nhật đơn đặt xưởng';
$this->params['breadcrumbs'][] = ['label' => 'Đặt xưởng', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'cập nhật';
$userAdmin = \backend\models\UserAdmin::findOne($model->admin_id);
?>
<div class="factory-update">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Người đặt</label>
                            <h2><?= $userAdmin->username ?></h2>
                        </div>
                        <div class="col-md-3">
                            <label for="">Số điện thoại</label>
                            <h2><?= $model->phone ?></h2>
                        </div>
                        <div class="col-md-3">
                            <label for="">Loại mẫu</label>
                            <h2><?= $model->loaimau->name ?></h2>
                        </div>
                        <div class="col-md-3">
                            <label for="">Ngày gửi mẫu</label>
                            <h2><?= date('d-m-Y',$model->created_at) ?></h2>
                        </div>
                    </div>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
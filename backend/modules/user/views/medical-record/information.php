<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\UserAdmin;

/* @var $this yii\web\View */
/* @var $model common\models\user\MedicalRecord */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Thông tin bổ xung bệnh án: '.$model->medical_record_id;
$this->params['breadcrumbs'][] = ['label' => 'Hồ sơ bệnh án', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="medical-record-information">

    <?php $form = ActiveForm::begin(); ?>
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
            <div class="clearfix"></div>
        </div>

        <?php $form = ActiveForm::begin(); ?>
        <div class="x_content">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab_content5" id="5-tab" role="tab" data-toggle="tab" aria-expanded="true">
                            Nhật ký điều trị
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#tab_content1" id="one-tab" role="tab" data-toggle="tab" aria-expanded="true">
                            Hỏi bệnh
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab_content2" id="two-tab" role="tab" data-toggle="tab" aria-expanded="true">
                            Khám bệnh
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab_content3" id="three-tab" role="tab" data-toggle="tab" aria-expanded="true">
                            Tổng kết bệnh án
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tab_content4" id="4-tab" role="tab" data-toggle="tab" aria-expanded="true">
                            Hình ảnh ban đầu
                        </a>
                    </li>

                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content5" aria-labelledby="5-tab">
                        <?= $this->render('partial/history', ['form' => $form, 'model' => $model,'id' => $id,'medical_record_history' => $medical_record_history]); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="one-tab">
                        <?= $this->render('partial/hoi_benh', ['form' => $form, 'model' => $model]); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="two-tab">
                        <?= $this->render('partial/kham_benh', ['form' => $form, 'model' => $model, 'images' => []]); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="three-tab">
                        <?= $this->render('partial/tong_ket', ['form' => $form, 'model' => $model]); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="4-tab">
                        <?= $this->render('partial/image', ['form' => $form, 'model' => $model,'images' => $images]); ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
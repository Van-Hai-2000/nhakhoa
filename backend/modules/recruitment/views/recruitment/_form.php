<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\recruitment\Recruitment */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        CKEDITOR.replace("recruitmentinfo-benefit", {
            height: 200,
            language: '<?php echo Yii::$app->language ?>'
        });
        CKEDITOR.replace("recruitmentinfo-description", {
            height: 200,
            language: '<?php echo Yii::$app->language ?>'
        });
        CKEDITOR.replace("recruitmentinfo-job_requirement", {
            height: 200,
            language: '<?php echo Yii::$app->language ?>'
        });
        CKEDITOR.replace("recruitmentinfo-record_consists", {
            height: 200,
            language: '<?php echo Yii::$app->language ?>'
        });
    });
</script>
<div class="recruitment-form">
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'recruitment-form',
                        'options' => [
                            'class' => 'form-horizontal'
                        ]
            ]);
            ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                    Thông tin cơ bản
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">
                                    Mô tả
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">
                                    SEO
                                </a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                <?= $this->render('partial/basicinfo', ['form' => $form, 'model' => $model]); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                <?= $this->render('partial/description', ['form' => $form, 'model' => $model, 'model_info' => $model_info]); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                <?= $this->render('partial/seo', ['form' => $form, 'model' => $model]); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

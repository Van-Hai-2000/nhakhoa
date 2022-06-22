<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SiteIntroduce */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        CKEDITOR.replace("siteintroduce-description", {
            height: 400,
            language: '<?php echo Yii::$app->language ?>'
        });
    });
</script>

<div class="site-introduce-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php $form = ActiveForm::begin(); ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'avatar') ?>
                            <div id="siteavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
                                <?php if ($model->avatar_path && $model->avatar_name) { ?>
                                <img src="<?= common\components\ClaHost::getImageHost(), $model->avatar_path, $model->avatar_name ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="siteavatar_form" style="display: inline-block;">
                                <?= Html::button('Chọn avatar', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            jQuery('#siteavatar_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['siteinfo/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function () {
                                },
                                onComplete: function (result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#siteintroduce-avatar').val(obj.data.avatar);
                                            if (jQuery('#siteavatar_img img').attr('src')) {
                                                jQuery('#siteavatar_img img').attr('src', obj.data.realurl);
                                            } else {
                                                jQuery('#siteavatar_img').append('<img src="' + obj.data.realurl + '" />');
                                            }
                                            jQuery('#siteavatar_img').css({"margin-right": "10px"});
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    

                    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>



</div>

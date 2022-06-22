<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Siteinfo */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    textarea {
        min-height: 150px;
    }
</style>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<div class="siteinfo-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'id' => 'siteinfo-form',
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'options' => [
                    'class' => 'form-horizontal',
                ]
            ]);
            ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <?=
                    $form->field($model, 'title', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập tiêu đề trang'
                    ])->label($model->getAttributeLabel('title'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'logo', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'logo') ?>
                            <div id="sitelogo_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">
                                <?php if ($model->logo) { ?>
                                    <img src="<?= $model->logo ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="sitelogo_form" style="display: inline-block;">
                                <?= Html::button('Chọn logo', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            jQuery('#sitelogo_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['siteinfo/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function() {},
                                onComplete: function(result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#siteinfo-logo').val(obj.data.avatar);
                                            if (jQuery('#sitelogo_img img').attr('src')) {
                                                jQuery('#sitelogo_img img').attr('src', obj.data.realurl);
                                            } else {
                                                jQuery('#sitelogo_img').append('<img src="' + obj.data.realurl + '" />');
                                            }
                                            jQuery('#sitelogo_img').css({
                                                "margin-right": "10px"
                                            });
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'favicon', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'favicon') ?>
                            <div id="sitefavicon_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">
                                <?php if ($model->favicon) { ?>
                                    <img src="<?= $model->favicon ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="sitefavicon_form" style="display: inline-block;">
                                <?= Html::button('Chọn favicon', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            jQuery('#sitefavicon_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['siteinfo/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function() {},
                                onComplete: function(result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#siteinfo-favicon').val(obj.data.avatar);
                                            if (jQuery('#sitefavicon_img img').attr('src')) {
                                                jQuery('#sitefavicon_img img').attr('src', obj.data.realurl);
                                            } else {
                                                jQuery('#sitefavicon_img').append('<img src="' + obj.data.realurl + '" />');
                                            }
                                            jQuery('#sitefavicon_img').css({
                                                "margin-right": "10px"
                                            });
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'footer_logo', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'footer_logo') ?>
                            <div id="site_footer_logo_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">
                                <?php if ($model->footer_logo) { ?>
                                    <img src="<?= $model->footer_logo ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="site_footer_logo_form" style="display: inline-block;">
                                <?= Html::button('Chọn logo chân trang', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            jQuery('#site_footer_logo_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['siteinfo/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function() {},
                                onComplete: function(result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#siteinfo-footer_logo').val(obj.data.avatar);
                                            if (jQuery('#site_footer_logo_img img').attr('src')) {
                                                jQuery('#site_footer_logo_img img').attr('src', obj.data.realurl);
                                            } else {
                                                jQuery('#site_footer_logo_img').append('<img src="' + obj.data.realurl + '" />');
                                            }
                                            jQuery('#site_footer_logo_img').css({
                                                "margin-right": "10px"
                                            });
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <?=
                    $form->field($model, 'time_commission', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập ngày'
                    ])->label($model->getAttributeLabel('time_commission'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'email', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập email'
                    ])->label($model->getAttributeLabel('email'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'email_rif', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'VD: a1@gmail,a2@gmail.com'
                    ])->label($model->getAttributeLabel('email_rif'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'phone', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập số điện thoại cố định'
                    ])->label($model->getAttributeLabel('phone'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'hotline', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập hotline'
                    ])->label($model->getAttributeLabel('hotline'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'company', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Tên công ty'
                    ])->label($model->getAttributeLabel('company'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'address', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập địa chỉ'
                    ])->label($model->getAttributeLabel('address'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'number_auth', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textArea([
                        'placeholder' => 'Giấy chứng nhận đăng ký kinh doanh'
                    ])->label($model->getAttributeLabel('number_auth'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'iframe', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập iframe bản đồ'
                    ])->label($model->getAttributeLabel('iframe'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'video_link', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập link video'
                    ])->label($model->getAttributeLabel('video_link'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'copyright', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập copyright'
                    ])->label($model->getAttributeLabel('copyright'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'link_bct', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập link bộ công thương'
                    ])->label($model->getAttributeLabel('link_bct'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'meta_keywords', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập meta keywords'
                    ])->label($model->getAttributeLabel('meta_keywords'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'meta_description', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'placeholder' => 'Nhập meta description'
                    ])->label($model->getAttributeLabel('meta_description'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>

                </div>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
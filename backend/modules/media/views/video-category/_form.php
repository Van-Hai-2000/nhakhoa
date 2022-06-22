<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $model common\models\NewsCategory */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<div class="news-category-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'news-category-form',
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => false,
                        'validateOnSubmit' => true,
                        'validateOnChange' => true,
                        'validateOnType' => true,
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
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'name', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'placeholder' => 'Nhập tên danh mục']) ?>
                            <?= Html::error($model, 'name', ['class' => 'help-block']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'link', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'link', ['class' => 'form-control', 'placeholder' => 'Nhập link video mặc định cho danh mục']) ?>
                            <?= Html::error($model, 'link', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'parent', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'parent', $model->optionsCategory(), ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'parent', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'avatar') ?>
                            <div id="categoryavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
                                <?php if ($model->avatar_path && $model->avatar_name) { ?>
                                    <img src="<?php echo ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="categoryavatar_form" style="display: inline-block;">
                                <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            jQuery('#categoryavatar_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['video-category/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function () {
                                },
                                onComplete: function (result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#videocategory-avatar').val(obj.data.avatar);
                                            if (jQuery('#categoryavatar_img img').attr('src')) {
                                                jQuery('#categoryavatar_img img').attr('src', obj.data.realurl);
                                            } else {
                                                jQuery('#categoryavatar_img').append('<img src="' + obj.data.realurl + '" />');
                                            }
                                            jQuery('#categoryavatar_img').css({"margin-right": "10px"});
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'meta_keywords', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'meta_keywords', ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'meta_keywords', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'meta_description', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'meta_description', ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'meta_description', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'meta_title', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'meta_title', ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'meta_title', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'status', [1 => 'Hiển thị', 0 => 'Ẩn'], ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
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

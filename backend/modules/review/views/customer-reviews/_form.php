<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\product\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>gentelella/starrr/dist/starrr.min.js"></script>
<?php $this->registerCssFile(Yii::$app->homeUrl . 'gentelella/starrr/dist/starrr.min.css'); ?>
<script type="text/javascript">
    //    jQuery(document).ready(function() {
    //        CKEDITOR.replace("product-short_description", {
    //            height: 400,
    //            language: '<?php // echo Yii::$app->language ?>'
    //        });
    //    });
</script>
<div class="customer-reviews-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
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
                    <!---->
                    <?= $form->field($model, 'customer_name', ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'])
                        ->textInput(['maxlength' => true])
                        ->label($model->getAttributeLabel('customer_name'), ['class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                        ]) ?>

                    <?= $form->field($model, 'customer_name_en', ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'])
                        ->textArea(['maxlength' => true, 'class' => 'form-control'])
                        ->label($model->getAttributeLabel('customer_name_en'), [
                            'class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                    <!---->
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'avatar') ?>
                            <div id="customerreviews_img"
                                 style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">
                                <?php if ($model->avatar_path && $model->avatar_name) { ?>
                                    <img src="<?php echo \common\components\ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>"
                                         style="width: 100%;"/>
                                <?php } ?>
                            </div>
                            <div id="customerreviews_form" style="display: inline-block;">
                                <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <!---->
                    <?= $form->field($model, 'score', ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12"><div class=\'starrr\' id=\'star2\'></div>{input}{error}{hint}</div>'])
                        ->hiddenInput(['class' => 'form-control'])
                        ->label($model->getAttributeLabel('score'), [
                            'class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                    <?= $form->field($model, 'review',
                        ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'])
                        ->textArea(['maxlength' => true, 'class' => 'form-control'])
                        ->label($model->getAttributeLabel('review'), ['class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                        ]) ?>
                    <?= $form->field($model, 'review_en', ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'])
                        ->textArea(['maxlength' => true, 'class' => 'form-control'])
                        ->label($model->getAttributeLabel('review_en'), ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                    <?= $form->field($model, 'customer_address', ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'])
                        ->textArea(['maxlength' => true, 'class' => 'form-control'])
                        ->label($model->getAttributeLabel('customer_address'), ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                    <?= $form->field($model, 'customer_address_en', ['template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'])
                        ->textArea(['maxlength' => true, 'class' => 'form-control'])
                        ->label($model->getAttributeLabel('customer_address_en'), ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#customerreviews_form').ajaxUpload({
            url: '<?= yii\helpers\Url::to(['customer-reviews/uploadfile']); ?>',
            name: 'file',
            onSubmit: function () {
            },
            onComplete: function (result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#customerreviews-avatar').val(obj.data.avatar);
                        if (jQuery('#customerreviews_img img').attr('src')) {
                            jQuery('#customerreviews_img img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#customerreviews_img').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#customerreviews_img').css({"margin-right": "10px"});
                    }
                }
            }
        });
    });
    var $s2input = $('#customerreviews-score');
    $('#star2').starrr({
        max: 5,
        rating: $s2input.val(),
        change: function (e, value) {
            $s2input.val(value).trigger('input');
        }
    });
</script>


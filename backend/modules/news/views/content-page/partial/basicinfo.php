<?php

use yii\helpers\Html;
use common\components\ClaHost;
use common\models\NewsCategory;
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'title', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'title', ['class' => 'form-control', 'placeholder' => 'Nhập tiêu đề bài tin']) ?>
        <?= Html::error($model, 'title', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeDropDownList($model, 'status', [1 => 'Hiển thị', 0 => 'Ẩn'], ['class' => 'form-control']) ?>
        <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeHiddenInput($model, 'avatar') ?>
        <div id="contentpageavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
            <?php if ($model->avatar_path && $model->avatar_name) { ?>
                <img src="<?php echo ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>" style="width: 100%;" />
            <?php } ?>
        </div>
        <div id="contentpageavatar_form" style="display: inline-block;">
            <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#contentpageavatar_form').ajaxUpload({
            url: '<?= yii\helpers\Url::to(['content-page/uploadfile']); ?>',
            name: 'file',
            onSubmit: function () {
            },
            onComplete: function (result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#contentpage-avatar').val(obj.data.avatar);
                        if (jQuery('#contentpageavatar_img img').attr('src')) {
                            jQuery('#contentpageavatar_img img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#contentpageavatar_img').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#contentpageavatar_img').css({"margin-right": "10px"});
                    }
                }
            }
        });
    });
</script>

<div class="form-group">
    <?= Html::activeLabel($model, 'short_description', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextarea($model, 'short_description', ['class' => 'form-control', 'placeholder' => 'Nhập mô tả ngắn cho bài tin', 'rows' => 5]) ?>
        <?= Html::error($model, 'short_description', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'description', ['class' => 'required col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?= Html::activeTextarea($model, 'description', ['class' => 'form-control', 'placeholder' => 'Nhập mô tả ngắn cho bài tin']) ?>
        <?= Html::error($model, 'description', ['class' => 'help-block']); ?>
    </div>
</div>
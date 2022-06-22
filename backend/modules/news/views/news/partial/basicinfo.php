<?php

use yii\helpers\Html;
use common\components\ClaHost;
use common\models\news\NewsCategory;
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'title', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'title', ['class' => 'form-control', 'placeholder' => 'Nhập tiêu đề bài tin']) ?>
        <?= Html::error($model, 'title', ['class' => 'help-block']); ?>
    </div>
</div>


<div class="form-group">
    <?= Html::activeLabel($model, 'category_id', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?php $model_cats = new NewsCategory(); ?>
        <?= Html::activeDropDownList($model, 'category_id', $model_cats->optionsCategory(), ['class' => 'form-control']) ?>
        <?= Html::error($model, 'category_id', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'author', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'author', ['class' => 'form-control', 'placeholder' => 'Nhập tên người viết']) ?>
        <?= Html::error($model, 'author', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'source', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'source', ['class' => 'form-control', 'placeholder' => 'Nhập nguồn bài tin (Sưu tầm, dịch...)']) ?>
        <?= Html::error($model, 'source', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeDropDownList($model, 'status', \common\models\news\News::optionStatus(), ['class' => 'form-control']) ?>
        <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'publicdate', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'publicdate', ['class' => 'form-control date-picker', 'required' => 'required']) ?>
        <?= Html::error($model, 'publicdate', ['class' => 'help-block']); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#news-publicdate').daterangepicker({
            timePicker: true,
            timePickerIncrement: 5,
            timePicker24Hour: true,
            locale: {
                format: 'DD-MM-YYYY HH:mm',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            },
            singleDatePicker: true,
            calender_style: "picker_4"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
</script>

<div class="form-group">
    <?= Html::activeLabel($model, 'ishot', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-7 col-sm-7 col-xs-12" style="padding-top: 8px;">
        <?= Html::activeCheckbox($model, 'ishot', ['class' => 'js-switch']) ?>
        <?= Html::error($model, 'ishot', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeHiddenInput($model, 'avatar') ?>
        <div id="newsavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
            <?php if ($model->avatar_path && $model->avatar_name) { ?>
                <img src="<?php echo ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>" style="width: 100%;" />
            <?php } ?>
        </div>
        <div id="newsavatar_form" style="display: inline-block;">
            <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
            <?= Html::error($model, 'avatar', ['class' => 'help-block']); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#newsavatar_form').ajaxUpload({
            url: '<?= yii\helpers\Url::to(['news/uploadfile']); ?>',
            name: 'file',
            onSubmit: function () {
            },
            onComplete: function (result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#news-avatar').val(obj.data.avatar);
                        if (jQuery('#newsavatar_img img').attr('src')) {
                            jQuery('#newsavatar_img img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#newsavatar_img').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#newsavatar_img').css({"margin-right": "10px"});
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
    <?= Html::activeLabel($model, 'description', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextarea($model, 'description', ['class' => 'form-control', 'placeholder' => 'Nội dung', 'rows' => 5]) ?>
        <?= Html::error($model, 'description', ['class' => 'help-block']); ?>
    </div>
</div>

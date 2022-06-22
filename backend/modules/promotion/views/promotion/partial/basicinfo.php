<?php

use yii\helpers\Html;
?>

<?=
$form->field($model, 'name', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'Nhập tên menu'
])->label($model->getAttributeLabel('name'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'startdate', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'startdate', ['class' => 'form-control date-picker', 'required' => 'required']) ?>
        <?= Html::error($model, 'startdate', ['class' => 'help-block']); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#promotions-startdate').daterangepicker({
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
    <?= Html::activeLabel($model, 'enddate', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'enddate', ['class' => 'form-control date-picker', 'required' => 'required']) ?>
        <?= Html::error($model, 'enddate', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'time_space', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'time_space', ['class' => 'form-control']) ?>
        <?= Html::error($model, 'time_space', ['class' => 'help-block']); ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#promotions-enddate').daterangepicker({
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
    <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeHiddenInput($model, 'avatar') ?>
        <div id="promotionsimage_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
            <?php if ($model->image_path && $model->image_name) { ?>
                <img src="<?php echo \common\components\ClaHost::getImageHost() . $model->image_path . 's100_100/' . $model->image_name; ?>" style="width: 100%;" />
            <?php } ?>
        </div>
        <div id="promotionsimage_form" style="display: inline-block;">
            <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#promotionsimage_form').ajaxUpload({
            url: '<?= yii\helpers\Url::to(['promotion/uploadfile']); ?>',
            name: 'file',
            onSubmit: function () {
            },
            onComplete: function (result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#promotions-avatar').val(obj.data.avatar);
                        if (jQuery('#promotionsimage_img img').attr('src')) {
                            jQuery('#promotionsimage_img img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#promotionsimage_img').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#promotionsimage_img').css({"margin-right": "10px"});
                    }
                }
            }
        });
    });
</script>

<?=
$form->field($model, 'sortdesc', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea([
    'class' => 'form-control',
    'placeholder' => 'Nhập mô tả ngắn'
])->label($model->getAttributeLabel('sortdesc'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'description', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea([
    'class' => 'form-control',
    'placeholder' => 'Nhập mô tả chi tiết'
])->label($model->getAttributeLabel('description'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'status', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px;">{input}{error}{hint}</div>'
])->checkbox([
    'class' => 'js-switch',
    'label' => NULL
])->label($model->getAttributeLabel('status'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'meta_title', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'meta_title'
])->label($model->getAttributeLabel('meta_title'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'meta_keywords', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'meta_keywords'
])->label($model->getAttributeLabel('meta_keywords'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'meta_description', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'meta_description'
])->label($model->getAttributeLabel('meta_description'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>
 <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
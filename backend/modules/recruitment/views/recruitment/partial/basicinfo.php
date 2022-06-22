<?php

use yii\helpers\Html;
use common\models\recruitment\Recruitment;
use common\models\recruitment\Category;
use common\models\recruitment\Skill;
use common\models\Province;
use common\components\ClaHost;
?>

<?=
$form->field($model, 'title', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput(['maxlength' => true])->label($model->getAttributeLabel('title'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeHiddenInput($model, 'avatar') ?>
        <div id="jobsavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
            <?php if ($model->avatar_path && $model->avatar_name) { ?>
                <img src="<?php echo ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>" style="width: 100%;" />
            <?php } ?>
        </div>
        <div id="jobsavatar_form" style="display: inline-block;">
            <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#jobsavatar_form').ajaxUpload({
            url: '<?= yii\helpers\Url::to(['/management/recruitment/uploadfile']); ?>',
            name: 'file',
            onSubmit: function () {
            },
            onComplete: function (result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#recruitment-avatar').val(obj.data.avatar);
                        if (jQuery('#jobsavatar_img img').attr('src')) {
                            jQuery('#jobsavatar_img img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#jobsavatar_img').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#jobsavatar_img').css({"margin-right": "10px"});
                    }
                }
            }
        });
    });
</script>

<?=
$form->field($model, 'level', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Recruitment::arrayLevel())->label($model->getAttributeLabel('level'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'category_id', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Category::optionsCategory(), [
    'multiple' => 'multiple'
])->label($model->getAttributeLabel('category_id'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'typeofworks', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Recruitment::arrayTypeofwork(), [
    'multiple' => 'multiple'
])->label($model->getAttributeLabel('typeofworks'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'locations', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Province::optionsProvince(), [
    'multiple' => 'multiple'
])->label($model->getAttributeLabel('locations'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<script>
    jQuery(document).ready(function () {
        jQuery("#recruitment-category_id").select2({
            placeholder: "Chọn ngành ngề",
            allowClear: true
        });
        jQuery("#recruitment-level").select2({
            placeholder: "Chọn cấp bậc",
            allowClear: true
        });
        jQuery("#recruitment-typeofworks").select2({
            placeholder: "Chọn loại hình công việc",
            allowClear: true
        });
        jQuery("#recruitment-locations").select2({
            maximumSelectionLength: 63,
            placeholder: "Chọn nơi làm việc",
            allowClear: true
        });
    });
</script>

<?=
$form->field($model, 'skills', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Skill::optionsSkill(), [
    'multiple' => 'multiple'
])->label($model->getAttributeLabel('skills'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>
<script>
    $(document).ready(function () {
        $("#recruitment-skills").select2({
            tags: true
        });
    });
</script>

<?=
$form->field($model, 'quantity', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput(['maxlength' => true])->label($model->getAttributeLabel('quantity'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'salaryrange', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeDropDownList($model, 'salaryrange', Recruitment::arraySalaryrange(), ['class' => 'form-control']) ?>
        <?= Html::error($model, 'salaryrange', ['class' => 'help-block']); ?>
        <div class="wrap-salary-detail row" style="display: <?php echo $model->salaryrange == Recruitment::SALARY_DETAIL ? 'block' : 'none' ?>">
            <div class="col-xs-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'currency', ['class' => 'control-label', 'style' => 'width: 100%;border-bottom: 1px solid #cccccc;text-align: center;']) ?>
                    <div class="col-xs-12" style="margin: 6px 0 0 20px;">
                        <!--<label style="cursor: pointer" for="Recruitment-currency-vnd">VNĐ:</label>--> 
                        <!--<input type="radio" class="flat" name="Recruitment[currency]" id="Recruitment-currency-vnd" value="1" <?php echo $model->currency == 1 ? 'checked' : '' ?> />--> 
                        <label style="cursor: pointer" for="Recruitment-currency-usd">USD:</label> 
                        <input type="radio" class="flat" name="Recruitment[currency]" id="Recruitment-currency-usd" value="2" <?php echo $model->currency == 2 ? 'checked' : '' ?> />
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <?= $form->field($model, 'salary_min')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-xs-4">
                <?= $form->field($model, 'salary_max')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#recruitment-salaryrange').change(function () {
            var salary = $(this).val();
            console.log(salary)
            if (salary == <?= Recruitment::SALARY_DETAIL ?>) {
                $('.wrap-salary-detail').show();
            } else {
                $('.wrap-salary-detail').hide();
            }
        });
    });
</script>


<?=
$form->field($model, 'knowledge', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Recruitment::arrayKnowledge())->label($model->getAttributeLabel('knowledge'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model, 'experience', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(Recruitment::arrayExperience())->label($model->getAttributeLabel('experience'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'publicdate', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'publicdate', ['class' => 'form-control date-picker', 'required' => 'required']) ?>
        <?= Html::error($model, 'publicdate', ['class' => 'help-block']); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#recruitment-publicdate').daterangepicker({
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
    <?= Html::activeLabel($model, 'expiration_date', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeTextInput($model, 'expiration_date', ['class' => 'form-control date-picker', 'required' => 'required']) ?>
        <?= Html::error($model, 'expiration_date', ['class' => 'help-block']); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#recruitment-expiration_date').daterangepicker({
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
    <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeDropDownList($model, 'status', [1 => 'Hiển thị', 0 => 'Ẩn'], ['class' => 'form-control']) ?>
        <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'ishot', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-7 col-sm-7 col-xs-12" style="padding-top: 8px;">
        <?= Html::activeCheckbox($model, 'ishot', ['class' => 'js-switch']) ?>
        <?= Html::error($model, 'ishot', ['class' => 'help-block']); ?>
    </div>
</div>
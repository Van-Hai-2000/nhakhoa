<?php

use yii\helpers\Html;
use common\components\ClaHost;
use common\models\Province;
?>

<?=

$form->field($model, 'name', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'Nhập tiêu đề video'
])->label($model->getAttributeLabel('name'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>
<?=

$form->field($model, 'link', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'Nhập link video từ youtube'
])->label($model->getAttributeLabel('link'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'category_id', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?php $model_cats = new common\models\media\VideoCategory(); ?>
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
    <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeDropDownList($model, 'status', [1 => 'Hiển thị', 0 => 'Ẩn'], ['class' => 'form-control']) ?>
        <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
    </div>
</div>

<?=
    $form->field($model, 'ishot', [
        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px;">{input}{error}{hint}</div>'
    ])->checkbox([
        'class' => 'js-switch',
        'label' => NULL
    ])->label($model->getAttributeLabel('ishot'), [
        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
    ])
?>

<?=
    $form->field($model, 'homeslide', [
        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px;">{input}{error}{hint}</div>'
    ])->checkbox([
        'class' => 'js-switch',
        'label' => NULL
    ])->label($model->getAttributeLabel('homeslide'), [
        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
    ])
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <?= Html::activeHiddenInput($model, 'avatar') ?>
        <div id="videoavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
            <?php if ($model->avatar_path && $model->avatar_name) { ?>
                <img src="<?php echo ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>" style="width: 100%;" />
            <?php } ?>
        </div>
        <div id="videoavatar_form" style="display: inline-block;">
            <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery('#videoavatar_form').ajaxUpload({
            url: '<?= yii\helpers\Url::to(['video/uploadfile']); ?>',
            name: 'file',
            onSubmit: function () {
            },
            onComplete: function (result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#video-avatar').val(obj.data.avatar);
                        if (jQuery('#videoavatar_img img').attr('src')) {
                            jQuery('#videoavatar_img img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#videoavatar_img').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#videoavatar_img').css({"margin-right": "10px"});
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
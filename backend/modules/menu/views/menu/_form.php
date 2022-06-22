<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\menu\Menu;
use common\models\menu\MenuGroup;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>

<div class="menu-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'menu-form',
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
                        <?= Html::activeLabel($model, 'group_id', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'group_id', MenuGroup::optionsMenuGroup(), ['class' => 'form-control', 'disabled' => 'disabled']) ?>
                            <?= Html::error($model, 'group_id', ['class' => 'help-block']); ?>
                        </div>
                    </div>

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
                    
                    <?=
                    $form->field($model, 'description', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textArea([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập mô tả'
                    ])->label($model->getAttributeLabel('description'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ]);
                    ?>
                    

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'parent', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'parent', $model->optionsMenu(), ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'parent', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'linkto', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px">
                            <input type="radio" class="flat linkto" name="Menu[linkto]" id="menu-linkto-internal" value="<?= Menu::LINKTO_INNER ?>" <?php echo $model->linkto == Menu::LINKTO_INNER ? 'checked' : '' ?> /> 
                            <label style="cursor: pointer" for="menu-linkto-internal">Trong website (Link nội bộ website)</label> 
                            <span>&nbsp;&nbsp;&nbsp;</span>
                            <input type="radio" class="flat linkto" name="Menu[linkto]" id="menu-linkto-external" value="<?= Menu::LINKTO_OUTER ?>" <?php echo $model->linkto == Menu::LINKTO_OUTER ? 'checked' : '' ?> />
                            <label style="cursor: pointer" for="menu-linkto-external">Ngoài website (Link bên ngoài website: vd: http://ketoanducminh.com)</label> 
                            <?= Html::error($model, 'linkto', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group" style="display: <?= $model->linkto == Menu::LINKTO_INNER ? 'block' : 'none' ?>">
                        <?= Html::activeLabel($model, 'link', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'values', Menu::getInnerLinks(), ['class' => 'form-control col-xs-12']) ?>
                            <?= Html::error($model, 'values', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group" style="display: <?= $model->linkto == Menu::LINKTO_OUTER ? 'block' : 'none' ?>">
                        <?= Html::activeLabel($model, 'link', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'link', ['class' => 'form-control', 'placeholder' => 'Nhập URL đích']) ?>
                            <?= Html::error($model, 'link', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'avatar') ?>
                            <div id="menuavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
                                <?php if ($model->avatar_path && $model->avatar_name) { ?>
                                    <img src="<?php echo ClaHost::getImageHost() . $model->avatar_path . 's100_100/' . $model->avatar_name; ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="menuavatar_form" style="display: inline-block;">
                                <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            jQuery('#menuavatar_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['/menu/menu/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function () {
                                },
                                onComplete: function (result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#menu-avatar').val(obj.data.avatar);
                                            if (jQuery('#menuavatar_img img').attr('src')) {
                                                jQuery('#menuavatar_img img').attr('src', obj.data.realurl);
                                            } else {
                                                jQuery('#menuavatar_img').append('<img src="' + obj.data.realurl + '" />');
                                            }
                                            jQuery('#menuavatar_img').css({"margin-right": "10px"});
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'order', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'order', ['class' => 'form-control', 'placeholder' => 'Nhập thứ tự']) ?>
                            <?= Html::error($model, 'order', ['class' => 'help-block']); ?>
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
                        <?= Html::activeLabel($model, 'target', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'target', [Menu::TARGET_UNBLANK => 'Trong tab hiện tại', Menu::TARGET_BLANK => 'Mở tab mới'], ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'target', ['class' => 'help-block']); ?>
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
<script>
    $(document).ready(function () {
        $("#menu-values").select2({});
        $('input.linkto').on("ifChanged", function () {
            var val = jQuery(this).val();
            if (val == <?= Menu::LINKTO_OUTER ?>) {
                jQuery('#menu-link').closest('.form-group').show();
                jQuery('#menu-values').closest('.form-group').hide();
            } else {
                jQuery('#menu-link').closest('.form-group').hide();
                jQuery('#menu-values').closest('.form-group').show();
                $("#menu-values").select2({});
            }
        });
    });
</script>

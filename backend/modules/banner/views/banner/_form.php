<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\banner\BannerGroup;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'banner-form',
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => false,
                        'validateOnSubmit' => true,
                        'validateOnChange' => true,
                        'validateOnType' => true,
                        'options' => [
                            'class' => 'form-horizontal',
                            'enctype' => 'multipart/form-data'
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
                        <?= Html::activeLabel($model, 'group_id', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'group_id', BannerGroup::optionsBannerGroup(), ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'group_id', ['class' => 'help-block']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'name', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'name', ['class' => 'form-control', 'placeholder' => 'Nhập tên banner']) ?>
                            <?= Html::error($model, 'name', ['class' => 'help-block']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'src', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px;">
                            <?php if ($model->id && $model->src) { ?>
                                <div style="display: block; margin-bottom: 15px;">
                                    <img style="max-width: 100px; max-height: 100px" src="<?php echo $model->src ?>" />
                                </div>
                            <?php } ?>
                            <?= Html::activeHiddenInput($model, 'src') ?>
                            <?= Html::fileInput('src'); ?>
                            <?= Html::error($model, 'src', ['class' => 'help-block']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'link', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'link', ['class' => 'form-control', 'placeholder' => 'Nhập link cho banner']) ?>
                            <?= Html::error($model, 'link', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'description', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextarea($model, 'description', ['class' => 'form-control', 'rows' => 4]) ?>
                            <?= Html::error($model, 'description', ['class' => 'help-block']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'order', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeTextInput($model, 'order', ['class' => 'form-control', 'placeholder' => 'Nhập thứ tự cho banner']) ?>
                            <?= Html::error($model, 'order', ['class' => 'help-block']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'target', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'target', [0 => 'Trong tab hiện tại', 1 => 'Mở tab mới'], ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'target', ['class' => 'help-block']); ?>
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
                        <?= Html::activeLabel($model, 'category_id', ['class' => 'required control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'category_id', (new \common\models\product\ProductCategory())->optionsCategory(), ['class' => 'form-control']) ?>
                            <?= Html::error($model, 'category_id', ['class' => 'help-block']); ?>
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

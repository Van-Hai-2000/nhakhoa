<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-admin-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'id' => 'user-admin-form',
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
                    <h3>Thông tin cá nhân </h3>
                    <div class="x_panel">
                        <input type="hidden" name="SignupForm[vai_tro]"
                               value="<?= \backend\models\UserAdmin::USER_DOCTOR ?>">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'fullname', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Họ và tên'
                            ])->label($model->getAttributeLabel('fullname'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'username', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Tên đăng nhập'
                            ])->label($model->getAttributeLabel('username'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'password', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->passwordInput([
                                'class' => 'form-control',
                                'placeholder' => 'Password'
                            ])->label($model->getAttributeLabel('password'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'password2', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->passwordInput([
                                'class' => 'form-control',
                                'placeholder' => 'Password2'
                            ])->label($model->getAttributeLabel('password2'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'email', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Email'
                            ])->label($model->getAttributeLabel('email'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::activeLabel($model, 'src', ['class' => 'required control-label col-md-4 col-sm-2 col-xs-12']) ?>
                                <div class="" style="padding-top: 8px;">
                                    <?php if ($model->src) { ?>
                                        <div style="display: block; margin-bottom: 15px;">
                                            <img style="max-width: 100px; max-height: 100px"
                                                 src="<?= \common\components\ClaHost::getImageHost() . $model->src ?>"/>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-8 col-sm-10 col-xs-12">
                                        <?= Html::activeHiddenInput($model, 'src') ?>
                                        <?= Html::fileInput('src'); ?>
                                        <?= Html::error($model, 'src', ['class' => 'help-block']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::activeLabel($model, 'branch_id', ['class' => 'control-label col-md-4 col-sm-2 col-xs-12']) ?>
                                <div class="col-md-8 col-sm-10 col-xs-12">
                                    <?= Html::activeDropDownList($model, 'branch_id', \common\models\branch\Branch::getBranch(), ['class' => 'form-control ', 'placeholder' => 'Password']) ?>
                                    <?= Html::error($model, 'branch_id', ['class' => 'help-block']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'phone', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Nhập số điện thoại'
                            ])->label($model->getAttributeLabel('phone'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'phone2', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Nhập số điện thoại 2'
                            ])->label($model->getAttributeLabel('phone2'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'identification', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Số chứng minh nhân dân'
                            ])->label($model->getAttributeLabel('identification'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'date_range_identification', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Ngày cấp CMND/CCCD',
                                'type' => 'date',
                                'value' => $model->date_range_identification ? date('Y-m-d' , $model->date_range_identification) : ""
                            ])->label($model->getAttributeLabel('date_range_identification'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'issued_by_identification', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Nơi Cấp CMND/CCCD'
                            ])->label($model->getAttributeLabel('issued_by_identification'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::activeLabel($model, 'image_identification_before', ['class' => 'required control-label col-md-4 col-sm-2 col-xs-12']) ?>
                                <div class="" style="padding-top: 8px;">
                                    <?php if ($model->image_identification_before) { ?>
                                        <div style="display: block; margin-bottom: 15px;">
                                            <img style="max-width: 100px; max-height: 100px"
                                                 src="<?= \common\components\ClaHost::getImageHost() . $model->image_identification_before ?>"/>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-8 col-sm-10 col-xs-12">
                                        <?= Html::activeHiddenInput($model, 'image_identification_before') ?>
                                        <?= Html::fileInput('image_identification_before'); ?>
                                        <?= Html::error($model, 'image_identification_before', ['class' => 'help-block']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= Html::activeLabel($model, 'image_identification_after', ['class' => 'required control-label col-md-4 col-sm-2 col-xs-12']) ?>
                                <div class="" style="padding-top: 8px;">
                                    <?php if ($model->image_identification_after) { ?>
                                        <div style="display: block; margin-bottom: 15px;">
                                            <img style="max-width: 100px; max-height: 100px"
                                                 src="<?= \common\components\ClaHost::getImageHost() . $model->image_identification_after ?>"/>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-8 col-sm-10 col-xs-12">
                                        <?= Html::activeHiddenInput($model, 'image_identification_after') ?>
                                        <?= Html::fileInput('image_identification_after'); ?>
                                        <?= Html::error($model, 'image_identification_after', ['class' => 'help-block']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Bằng cấp</h3>
                    <div class="x_panel">

                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'degree', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->dropDownList(\backend\models\UserAdmin::getDegree(), ['prompt' => 'Bằng cấp'])->label($model->getAttributeLabel('degree'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                            <div class="col-md-6">
                        <?=
                        $form->field($model, 'name_training_unit', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nhập tên đơn vị đào tạo'
                        ])->label($model->getAttributeLabel('name_training_unit'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                            </div>
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'graduation_year', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nhập năm tốt nghiệp'
                        ])->label($model->getAttributeLabel('graduation_year'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'specialist', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Chuyên khoa'
                        ])->label($model->getAttributeLabel('specialist'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                    </div>
                    <h3>Chứng chỉ hành nghề/Chuyên môn</h3>
                    <div class="x_panel">
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'number_of_certificates', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nhập số chứng chỉ hành nghề',

                        ])->label($model->getAttributeLabel('number_of_certificates'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'date_range_certificates', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nhập ngày cấp chứng chỉ',
                            'type' => 'date',
                            'value' => $model->date_range_certificates ? date('Y-m-d' , $model->date_range_certificates) : ""
                        ])->label($model->getAttributeLabel('date_range_certificates'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'issued_by_certificates', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Nhập nơi cấp chứng chỉ',
                        ])->label($model->getAttributeLabel('issued_by_identification'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'work_experience', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Kinh nghiệm làm việc'
                        ])->label($model->getAttributeLabel('work_experience'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'specialize', [
                                'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                            ])->dropDownList(\backend\models\UserAdmin::getSpecialize(), ['prompt' => 'Chuyên môn'])->label($model->getAttributeLabel('specialize'), [
                                'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                        <?=
                        $form->field($model, 'contract_status', [
                            'template' => '{label}<div class="col-md-8 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                        ])->dropDownList(\backend\models\UserAdmin::getContractStatus(), ['prompt' => 'Hợp đồng'])->label($model->getAttributeLabel('contract_status'), [
                            'class' => 'control-label col-md-4 col-sm-2 col-xs-12'
                        ])
                        ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= Html::activeLabel($model, 'status', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeDropDownList($model, 'status', [1 => 'Kích hoạt', 0 => 'Dừng hoạt động'], ['class' => 'form-control', 'placeholder' => 'Password']) ?>
                            <?= Html::error($model, 'status', ['class' => 'help-block']); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($isNewRecord ? 'Create' : 'Update', ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<script>
    $( document ).ready(function() {
        var checkDegre= $("#signupform-degree");
        var name_training_unit= $("#signupform-name_training_unit");
        var graduation_year = $("#signupform-graduation_year");
        var specialist = $("#signupform-specialist");
        var certificates = $("#signupform-number_of_certificates");
        var date_certificates = $("#signupform-date_range_certificates");
        var issued_certificates = $("#signupform-issued_by_certificates");
        if(checkDegre.val() == ""){
            name_training_unit.prop("disabled", true);
            graduation_year.prop("disabled", true);
            specialist.prop("disabled", true);
        }
        if(certificates.val() == ""){
            date_certificates.prop("disabled", true);
            issued_certificates.prop("disabled", true);
        }
        $(checkDegre).change(function () {
            var self = this;
            var data = $(self).val();

            if(data == 0 || data == ''){
                name_training_unit.prop("disabled", true);
                graduation_year.prop("disabled", true);
                specialist.prop("disabled", true);
            }
            else if(data == 1){
                name_training_unit.prop("disabled", false);
                graduation_year.prop("disabled", false);
                specialist.prop("disabled", true);
            }else
            {
                name_training_unit.prop("disabled", false);
                graduation_year.prop("disabled", false);
                specialist.prop("disabled", false);
            }
        })
        $(certificates).focusout(function(){
            if(certificates.val() == ""){
                date_certificates.prop("disabled", true);
                issued_certificates.prop("disabled", true);
            }
            else
            {
                date_certificates.prop("disabled", false);
                issued_certificates.prop("disabled", false);
            }

        });
        // $( "p" ).focusin(function() {
        //     $( this ).find( "span" ).css( "display", "inline" ).fadeOut( 1000 );
        // });

    });
</script>
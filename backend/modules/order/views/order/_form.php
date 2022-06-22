<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\order\Order;

/* @var $this yii\web\View */
/* @var $model common\models\order\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'order-form',
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

                    <?=
                    $form->field($modelitem, 'product_id', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập mã sản phẩm'
                    ])->label($modelitem->getAttributeLabel('product_id'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?=
                    $form->field($model, 'name', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tên khách hàng'
                    ])->label($model->getAttributeLabel('name'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?=
                    $form->field($model, 'facebook', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập link facebook khách hàng'
                    ])->label($model->getAttributeLabel('facebook'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?php
//                    echo $form->field($model, 'email', [
//                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
//                    ])->textInput([
//                        'class' => 'form-control',
//                        'placeholder' => 'Nhập email khách hàng'
//                    ])->label($model->getAttributeLabel('email'), [
//                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
//                    ])
                    ?>

                    <?=
                    $form->field($model, 'phone', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập số điện thoại khách hàng'
                    ])->label($model->getAttributeLabel('phone'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?=
                    $form->field($model, 'address', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập địa chỉ khách hàng'
                    ])->label($model->getAttributeLabel('address'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?=
                    $form->field($model, 'money_customer_transfer', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Nhập số tiền khách hàng thông báo đã chuyển khoản'
                    ])->label($model->getAttributeLabel('money_customer_transfer'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?=
                    $form->field($model, 'bank_transfer', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->dropDownList(Order::arrayBankTransfer(), [
                        'class' => 'form-control',
                        'prompt' => '--- Chọn ngân hàng ---'
                    ])->label($model->getAttributeLabel('bank_transfer'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?=
                    $form->field($model, 'confirm_customer_transfer', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px">{input}{error}{hint}</div>'
                    ])->checkbox([
                        'class' => 'js-switch'
                    ])->label($model->getAttributeLabel('confirm_customer_transfer'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

                    <?php
//                    echo $form->field($model, 'province_id', [
//                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
//                    ])->dropDownList($provinces, [
//                        'class' => 'select-province-id form-control',
//                        'prompt' => '--- Chọn tỉnh/thành phố ---'
//                    ])->label($model->getAttributeLabel('province_id'), [
//                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
//                    ])
                    ?>

                    <?php
//                    echo $form->field($model, 'district_id', [
//                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
//                    ])->dropDownList($districts, [
//                        'class' => 'select-district-id form-control',
//                        'prompt' => '--- Chọn quận/huyện ---'
//                    ])->label($model->getAttributeLabel('district_id'), [
//                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
//                    ])
                    ?>

                    <?=
                    $form->field($model, 'note', [
                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
                    ])->textArea([
                        'class' => 'form-control',
                        'rows' => 4
                    ])->label($model->getAttributeLabel('note'), [
                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
                    ])
                    ?>

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
//    $(document).ready(function () {
//        $('.select-province-id').change(function () {
//            var province_id = $(this).val();
//            $.getJSON(
//                    "<?= \yii\helpers\Url::to(['/suggest/getdistrict']) ?>",
//                    {province_id: province_id, label: 'Quận/huyện'}
//            ).done(function (data) {
//                $('.select-district-id').html(data.html);
//            }).fail(function (jqxhr, textStatus, error) {
//                var err = textStatus + ", " + error;
//                console.log("Request Failed: " + err);
//            });
//        });
//    });
</script>

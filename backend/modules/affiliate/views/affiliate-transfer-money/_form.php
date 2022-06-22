<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $model common\models\affiliate\AffiliateTransferMoney */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<div class="affiliate-transfer-money-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                        'fieldClass' => 'common\components\MyActiveField'
            ]);
            ?>

            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Tên người yêu cầu</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <p style="padding-top: 8px">
                                <?php
                                $user = common\models\User::findOne($model->user_id);
                                echo $user['username'];
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Thông tin chuyển khoản</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <p style="padding-top: 8px">
                                <?php
                                $info = common\models\affiliate\AffiliatePaymentInfo::findOne($model->user_id);
                                echo nl2br($info['payment_info']);
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Số tiền yêu cầu chuyển</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <p style="padding-top: 8px">
                                <?php
                                echo number_format($model->money, 0, ',', '.');
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Ghi chú của khách hàng</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <p style="padding-top: 8px">
                                <?php
                                echo $model->note
                                ?>
                            </p>
                        </div>
                    </div>


                    <?= $form->field($model, 'status')->dropDownList(common\models\affiliate\AffiliateTransferMoney::arrStatus()) ?>

                    <?= $form->field($model, 'note_admin')->textarea() ?>

                    <div class="form-group">
                        <?= Html::activeLabel($model, 'avatar', ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) ?>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?= Html::activeHiddenInput($model, 'avatar') ?>
                            <div id="newsavatar_img" style="display: inline-block; max-width: 100px; max-height: 100px; overflow: hidden; vertical-align: top;">  
                                <?php if ($model->image_path && $model->image_name) { ?>
                                    <img src="<?php echo ClaHost::getImageHost() . $model->image_path . 's100_100/' . $model->image_name; ?>" style="width: 100%;" />
                                <?php } ?>
                            </div>
                            <div id="newsavatar_form" style="display: inline-block;">
                                <?= Html::button('Chọn ảnh đại diện', ['class' => 'btn']); ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            jQuery('#newsavatar_form').ajaxUpload({
                                url: '<?= yii\helpers\Url::to(['/affiliate/affiliate-transfer-money/uploadfile']); ?>',
                                name: 'file',
                                onSubmit: function () {
                                },
                                onComplete: function (result) {
                                    var obj = $.parseJSON(result);
                                    if (obj.status == '200') {
                                        if (obj.data.realurl) {
                                            jQuery('#affiliatetransfermoney-avatar').val(obj.data.avatar);
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
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

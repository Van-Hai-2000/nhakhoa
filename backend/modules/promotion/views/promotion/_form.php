<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\product\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/ajaxupload.min.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        CKEDITOR.replace("promotions-description", {
            height: 400,
            language: '<?php echo Yii::$app->language ?>'
        });

    });
</script>
<div class="product-form">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'product-form',
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

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="one-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                    <?= Yii::t('app', 'basic_info') ?>
                                </a>
                            </li>
                            <!-- <li role="presentation">
                                <a href="#tab_content2" role="tab" id="three-tab" data-toggle="tab" aria-expanded="false">
                                    <?= Yii::t('app', 'list_product') ?>
                                </a>
                            </li> -->
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="one-tab">
                                <?= $this->render('partial/basicinfo', ['form' => $form, 'model' => $model]); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="two-tab">
                                <?php if(!$model->isNewRecord) {?>
                                    <?= $this->render('partial/products', ['products' => $products, 'model' => $model]); ?>
                                <?php } else { ?>
                                    <p><?= Yii::t('app', 'please_create_promotion_before_update_product') ?></p>
                                <?php } ?>
                            </div>
                           
                        </div>
                    </div>

                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

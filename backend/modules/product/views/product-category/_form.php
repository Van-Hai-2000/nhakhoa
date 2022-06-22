<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\product\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    <?php if($model->ckedit_desc) { ?>
        jQuery(document).ready(function () {
            CKEDITOR.replace("productcategory-description", {
                height: 400,
                language: '<?php echo Yii::$app->language ?>'
            });
        });
    <?php } ?>
    jQuery(document).ready(function () {
            $('#productcategory-ckedit_desc').on("click", function () {
                if (this.checked) {
                    CKEDITOR.replace("productcategory-description", {
                        height: 400,
                        language: '<?php echo Yii::$app->language ?>'
                    });
                } else {
                    var a = CKEDITOR.instances['productcategory-description'];
                    if (a) {
                        a.destroy(true);
                    }

                }
            });
        });

    function formatMoney(a,c, d, t){
        var n = a, 
        c = isNaN(c = Math.abs(c)) ? 2 : c, 
        d = d == undefined ? "." : d, 
        t = t == undefined ? "," : t, 
        s = n < 0 ? "-" : "", 
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
        j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
</script>
<style>
    @media (min-width: 768px) {
        .form-horizontal .control-label {
            text-align: left;
        }
    }
</style>
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
                                    Thông tin cơ bản
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_content2" id="two-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                    Ảnh
                                </a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="one-tab">
                                <?= $this->render('partial/basicinfo', ['form' => $form, 'model' => $model]); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="two-tab">
                                <?= $this->render('partial/image', ['form' => $form, 'model' => $model, 'images' => $images]); ?>
                            </div>
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

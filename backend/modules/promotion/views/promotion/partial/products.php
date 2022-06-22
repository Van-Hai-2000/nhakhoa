<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\product\Product;
?> 
<style type="text/css">
    .col-md-6 {
        max-height: 82vh;
        overflow-y: auto;
    }
    .header-both p{
        font-size: 21px;
        font-weight: bold;
    }
    .header p{
        font-size: 20px;
    }
    td>a {
        display: block;
        width: 100%;
        height: 100%;
    }
    td>a i {
        font-size: 15px !important;
    }
    .click {
        cursor: pointer;
    }
    .box-crop {
        position: fixed;
        z-index: 1;
        top: 0px;
        background: #00000069;
        width: 100%;
        left: 0px;
        height: 100vh;
        /*display: flex;*/
        display: none;
    }
    .inbox {
        position: relative;
        width: 80%;
        margin: auto;
        background: #fff;
        padding: 10px;
        height: 90vh;
    }
    .close-box-crops {
        float: right;
        margin-right: 0px;
        margin-top: 0px;
        width: 25px;
        height: 25px;
        border: 1px solid red;
        padding: 0px 7px;
        cursor: pointer;
        font-weight: bold;
        font-size: 19px;
        line-height: 20px;
    }
</style>
<div class="x_panel" id="box-product-saved">
    <?= $this->render('listproduct', ['products' => $products]); ?>
</div>

<div class="box-crop">
    <div class="inbox">
        <div class="close-box-crops">x</div>
        <div class="content">
            <div class="header-both">
                <p><?= Yii::t('app', 'add_product_to_promotion') ?> </p>
            </div>
            <div class="col-md-6">
                <div class="header">
                    <p><?= Yii::t('app', 'search_product') ?></p>
                    <div class="box-form">
                        <input type="" name="product_name" id="product_name">
                        <a class="btn btn-success click search-product" id="search-product"><?= Yii::t('app', 'search') ?></a>
                    </div>
                </div>
                <div id="box-product-add">
                </div>
            </div>
            <div class="col-md-6">
                <div class="header">
                    <p><?= Yii::t('app', 'product_selected') ?></p>
                    <div class="box-form">
                        <a class="btn btn-success click save-promotion-add"><?= Yii::t('app', 'save') ?></a>
                    </div>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?= Yii::t('app', 'product') ?></th>
                            <th class="action-column">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="product-selected">
                      
                    </tbody>
                </table>
            </div>
            <div class="box-value">
                <input type="hidden" id="input-value" name="input-value">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.save-promotion-add').click(function() {
            $('.box-crop').css('display', 'none');
            $('.add-new-product').css('display', 'inline-block');
            var val = $('#input-value').val();
            jQuery.ajax({
                url: "<?= \yii\helpers\Url::to(['/promotion/promotion/save-product']) ?>",
                data: {promotion_id : <?= $model->id ?>,val: val},
                beforeSend: function () {
                },
                success: function (res) {
                    $('#product-selected').html('');
                    $('#input-value').val('');
                    $('#box-product-saved').html(res);
                },
                error: function () {
                }
            });
        });
        $('#search-product').click(function() {
            $('#search-product').css('display', 'none');
            jQuery.ajax({
                url: "<?= \yii\helpers\Url::to(['/promotion/promotion/get-product']) ?>",
                data:{keyword : $('#product_name').val()},
                beforeSend: function () {
                },
                success: function (res) {
                    $('#box-product-add').html(res);
                    $('#search-product').css('display', 'inline-block');
                },
                error: function () {
                }
            });
        });
        
        $('.close-box-crops').click(function() {
            if(confirm('<?= Yii::t('app', 'cancer_sure') ?>')) {
                $('.box-crop').css('display', 'none');
                $('.add-new-product').css('display', 'inline-block');
            }
        });
    });
    jQuery(document).on('click', '.remove-promotion-add', function () {
        var _this = $(this);
        var id = _this.attr('data-id');
        $('#input-value').val($('#input-value').val().replace(','+id, ''));
        _this.parent().parent().remove();
    });

    jQuery(document).on('click', '.product-page', function () {
        var _this = $(this);
        var page = _this.attr('data-page');
        jQuery.ajax({
            url: "<?= \yii\helpers\Url::to(['/promotion/promotion/get-product']) ?>",
            data:{page : page},
            beforeSend: function () {
            },
            success: function (res) {
                $('#box-product-add').html(res);
            },
            error: function () {
            }
        });
    });
</script> 
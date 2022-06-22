<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\product\Product;
?> 
<style type="text/css">
    li {
        list-style: none;
    }
    .paginations {
        max-width: 100%;
        overflow: auto;
        display: flex;
    }
    .box-table {
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
    .br-red {
        border: 1px solid #ff090999;
    }
    td input {
        border: 1px solid #ebebeb;
        height: 36px;
        padding: 7px;
    }
    .box-form {
        margin-bottom: 15px;
        overflow: hidden;
    }
    .box-form input,  .box-form select{
        border: 1px solid #ebebeb;
        height: 34px;
        padding: 7px;
        width: 75%;
        float: left;
        margin-right: 5%;
    }

    .box-form a {
        width: 18%;
        float: right;
        margin-right: 0px;
    }
</style>
<div class="inboxs">
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
                <?= $this->render('partial/productaddspace', ['product_add' => $product_add,
                            'count_page' => $count_page,
                            'page' => $page,
                        ]); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="header">
                <p>Sản phẩm đã chọn trong thời gian khuyến mãi:</p>
                <div class="box-form">
                    <?php 
                        $list_time = explode(' ', $model->time_space);
                        $hour = $model->getHourNow();
                    ?>
                    <select id="hour-promotion">
                        <?php for ($i=0; $i < count($list_time); $i++) {  ?>
                            <option value="<?= $list_time[$i] ?>" <?= $list_time[$i] == $hour ? 'selected' : '' ?>><?= $list_time[$i] ?>h - <?= isset($list_time[$i+1]) ? $list_time[$i+1] : $list_time[0] ?>h</option>
                        <?php } ?>
                    </select>
                    <!-- <input type="text" id="day-promotion" class="day-promotion date-picker"  value="<?= date('d-m-Y', time()) ?>" >
                    <script>
                        $(document).ready(function () {
                            $('#day-promotion').daterangepicker({
                                // timePicker: true,
                                timePickerIncrement: 5,
                                minDate: moment({day:<?= ($model->startdate < time()) ? date('d', time()) : date('d', $model->startdate) ?>}),
                                // maxDate: moment({day:28, month:12}),
                                // timePicker24Hour: true,
                                locale: {
                                    format: 'DD-MM-YYYY',
                                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                                    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                                },
                                singleDatePicker: true,
                                calender_style: "picker_4"
                            }, function (start, end, label) {
                                console.log(start.toISOString(), end.toISOString(), label);
                            });
                        });
                    </script> -->
                    <a class="btn btn-success click save-promotion-add right"><?= Yii::t('app', 'save') ?></a>
                    <p id="box-response"></p>
                </div>
            </div>
            <script type="text/javascript">
                function loadProAfter() {
                    var hour = $('#hour-promotion').val();
                    // var day = $('#day-promotion').val();
                    var day = 0;
                    var id = '<?= $id ?>';
                    loadAjax('<?= Url::to(['/promotion/promotion/load-product']) ?>', {id: id, hour: hour, day :day}, $('#product-selected-after'));
                }
            </script>
            <div class="box-table">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm chưa lưu</th>
                            <th>Giá khuyến mãi</th>
                            <th>Số lượng bán</th>
                            <th class="action-column">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="product-selected">
                        
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm đã lưu(<span id="count-product"><?= count($products) ?></span>)</th>
                            <th>Giá khuyến mãi</th>
                            <th>Số lượng bán</th>
                            <th class="action-column">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="product-selected-after">
                        <?php foreach ($products as $product) { 
                            $text_price = number_format($product['price'], 0, ',', '.');
                            $price = $product['price'];
                            if ($product['price_range']) {
                                $price_range = explode(',', $product['price_range']);
                                $price = $price_range[0];
                                $price_max = number_format($price_range[0], 0, ',', '.');
                                $price_min = number_format($price_range[count($price_range) - 1], 0, ',', '.');
                                $text_price = $price_max != $price_min ? $price_min . ' - ' . $price_max : $price_min;
                            }
                            ?>
                            <tr data-key="<?= $product['id_promotion_sale'] ?>"> 
                                <td><?= $product['name'] ?>(<?= $text_price ?>)</td>
                                <td><input class="price-sale" placeholder="nhập giá khuyến mãi" type="number" max="<?= $price ?>" value="<?= $product['price_promotion_sale'] ?>" /></td>
                                <td><input class="quantity-sale" placeholder="nhập số lượng" type="number" value="<?= $product['quantity_promotion_sale'] ?>"  /></td>
                                <td><a class="click remove-after" title="Xóa" data-id="<?= $product['id_promotion_sale'] ?>" data-product-id="<?= $product['id'] ?>" aria-label ><span class="glyphicon glyphicon-trash"></span></a></td>
                            </tr>
                        <?php } ?>
                        <input type="hidden" id="input-value-after" name="input-value-after" value="<?php if($products) foreach ($products as $product) echo ','.$product['id']; ?>">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-value">
            <input type="hidden" id="input-value" name="input-value" value="">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on( "change", "#hour-promotion", function() {
            loadProAfter();
            jQuery.ajax({
                url: "<?= \yii\helpers\Url::to(['/promotion/promotion/get-product-space']) ?>",
                data:{page : 1, hour: $(this).val()},
                beforeSend: function () {
                },
                success: function (res) {
                    $('#box-product-add').html(res);
                },
                error: function () {
                }
            });
        });
        // $(document).on( "change", "#day-promotion", function() {
        //     loadProAfter();
        // });
        $(document).on( "click", ".remove-after", function() {
            if(!confirm('Xóa sản phẩm. Nếu bạn đồng ý sản phẩm sẽ bị xóa khỏi chương trình khuyến mãi.')) {
                return false;
            }
            var _this = $(this);
            var id = _this.attr('data-id');
            var product_id = _this.attr('data-product-id');
            $('#input-value-after').val($('#input-value-after').val().replace(','+product_id, ''));
            _this.parent().parent().remove();

            loadAjax('<?= Url::to(['/promotion/promotion/delete-product']) ?>', {id : id}, $('.fdsfd'));
        });
        $('.save-promotion-add').click(function() {
            var val = $('#input-value').val()+$('#input-value-after').val();
            var price = '0';
            var quantity = '0';
            var kt = false;
            $('.price-sale').each(function() {
                if($(this).val()) {
                    if(parseFloat($(this).val()) >= parseFloat($(this).attr('max'))) {
                        kt = 2;
                        $(this).addClass('br-red');
                        return false;
                    }
                    price += ' '+$(this).val(); 
                } else {
                    $(this).addClass('br-red');
                    kt = true;
                    return false;
                }
            });
            $('.quantity-sale').each(function() {
                if($(this).val()) {
                    quantity += ' '+$(this).val(); 
                } else {
                    $(this).addClass('br-red');
                    kt = true;
                    return false;
                }
            });
            if(kt) {
                if(kt == 2) {
                    alert('Vui lòng nhập đúng giá khuyến mãi(nhỏ hơn giá bán)!')
                } else {
                    alert('Vui lòng nhập đầy đủ giá, số lượng khuyến mãi cho sản phẩm.');
                }
                return false;
            }
            var hour = $('#hour-promotion').val();
            // var day = $('#day-promotion').val();
            var day = 0;
            if(val && hour) {
                $('.box-crop').css('display', 'none');
                $('.add-new-product').css('display', 'inline-block');
                jQuery.ajax({
                    url: "<?= \yii\helpers\Url::to(['/promotion/promotion/save-product-space']) ?>",
                    data: {promotion_id : <?= $model->id ?>,val: val, price:price, quantity :quantity, hour: hour, day: day},
                    beforeSend: function () {
                    },
                    success: function (res) {
                        $('#product-selected').html('');
                        $('#input-value').val('');
                        $('#box-response').html(res);
                        loadProAfter();
                    },
                    error: function () {
                    }
                });
            } else {
                alert('Vui lòng chọn đầy đủ thông tin: giờ bắt đầu, sản phẩm.');
            }
        });
        $('#search-product').click(function() {
            $('#search-product').css('display', 'none');
            jQuery.ajax({
                url: "<?= \yii\helpers\Url::to(['/promotion/promotion/get-product-space']) ?>",
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
            url: "<?= \yii\helpers\Url::to(['/promotion/promotion/get-product-space']) ?>",
            data:{page : page, promotion_id: <?= isset($_GET['id']) ? $_GET['id'] : 0 ?>},
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
<?php
use yii\helpers\Html;
?>


<div class="box-banbuon">
    <label for="">Giá sản phẩm bán buôn</label>
    <div class="row" id="price-range-box">
        <?php 
            // if (!($model['price_range'][0] > 1)) die();
            $price_range = explode(',', $model['price_range']);
            if(count($price_range) && $price_range[0] > 0) { 
                $quality_range = explode(',', $model['quality_range']);
            ?>
            <?php for ($i=0; $i <count($price_range); $i++) { ?>
                <div class="price-range-box">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <input class="width-10 quality-ranges quality-range-input" placeholder="Ví dụ: Từ 1 hộp" name="Product[quality_range][]" value="<?= $quality_range[$i] ?>" type="number"> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <input type="number" class="width-10 quality-ranges quality-range-input_tg" placeholder="Đến 10 hộp" name="count[]"  value="<?= (isset($quality_range[$i+1]) && $quality_range[$i+1] ) ? $quality_range[$i+1] : '' ?>"  id="quantity-base" > 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <input class="width-30 price-range-input change-price-s" placeholder="Giá mua 1 hộp là 20.000" name="Product[price_range][]"  id="price-base" value="<?= $price_range[$i] ?>">
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="price-range-box">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <input class="width-10 quality-ranges quality-range-input" placeholder="Ví dụ: Từ 1 hộp" name="Product[quality_range][]"  type="number"> 
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <input type="number" class="width-10 quality-ranges quality-range-input_tg" placeholder="Đến 10 hộp" name="count[]"  value=""  id="quantity-base" > 
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <input class="width-30 price-range-input change-price-s" placeholder="Giá mua 1 hộp là 20.000" name="Product[price_range][]"  id="price-base" value="">
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="btn-add-price">
        <a id="remove-price-range-box" style="background: red"><i class="fa fa-minus"></i></a>
        <a id="add-price-range-box"><i class="fa fa-plus"></i></a>
    </div>
    <input id="last-quality_range" name="Product[quality_range][]" value="<?= (isset($quality_range[count($price_range)]) && $quality_range[count($price_range)] >1) ? $quality_range[count($price_range)] : '' ?>"  type="hidden"> 
</div>
<script type="text/javascript">
    function  checkrange() {
        var vl = $('.quality-ranges');
        for (var i = vl.length - 1; i > 0; i--) {
            if(parseInt($(vl[i]).val())  < parseInt($(vl[i-1]).val())) {
                $(vl[i]).css('border','1px solid red');
                alert('Số lượng mốc sau phải lớn hơn số lượng mốc trước');
                return true;
            }
            $(vl[i]).css('border','1px solid #ccc');
        }
        return false;
    }
    $(document).on( "change", ".quality-ranges", function() {
        checkrange();
    });
    $(document).on( "change", ".quality-range-input_tg", function() {
        $('#last-quality_range').val($('.quality-range-input_tg').last().val());
    });
    $(document).on( "keydown", ".change-price-s", function() {
        $(this).addClass("change-price-sactive");
        setTimeout(function(){
            var tg =$(".change-price-sactive");
            tg.val(tg.val().replace(/\./g, ""));
            tg.val(formatMoney(tg.val(),0, ',', '.'));
            tg.removeClass('change-price-sactive');
        }, 150);
    });
    $(document).ready(function() {
        $('#add-price-range-box').click(function() {
            var vl = $('#price-range-box').find('input');
            for (var i = vl.length - 1; i >= 0; i--) {
                if($(vl[i]).val() == '' || $(vl[i]).val()=='0') {
                    alert('Vui lòng điền đầy đủ thông tin mốc trước');
                    $(vl[i]).css('border','1px solid red');
                    return;
                } else {
                    $(vl[i]).css('border','1px solid #ccc');
                }
            }
            var vls = $('.quality-range-input_tg').last().val();
            var html = '<div class="price-range-box"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><input class="width-10 quality-ranges quality-range-input" placeholder="Từ" name="Product[quality_range][]"  value="'+(parseInt(vls)+1)+'" type="number"></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><input class="width-10 quality-ranges quality-range-input_tg" placeholder="Đến" name="count[]"  type="number"></div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><input class="width-30 price-range-input change-price-s" placeholder="Nhập giá" name="Product[price_range][]" ></div></div>';
            $('#price-range-box').append(html);
            $('#last-quality_range').val('');
        });

        $('#remove-price-range-box').click(function() {
            if(confirm('bạn có chắc chắn muốn xóa?')){
                var count =  $('.price-range-box').length;
                if(count > 1) {
                    var box = $('.price-range-box').last();
                    box.remove();
                    $('#last-quality_range').val($('.quality-range-input_tg').last().val());
                } else {
                    $('.quality-range-input').val('');
                    $('.quality-range-input_tg').val('');
                    $('.price-range-input').val('');
                    $('#last-quality_range').val('');
                }
                $('#last-quality_range').val($('.quality-range-input_tg').last().val());
            }
        });
    });
</script>

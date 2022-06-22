<?php 
$id = (isset($options['id']) && $options['id']) ? $options['id'] : 'find-ajax-'.$model->attribute;
$showid = $id.'show';
$placeholder = (isset($options['placeholder']) && $options['placeholder']) ? $options['placeholder'] : $model->getAttrName();
$value = $model->model[$model->attribute];
?>
<div class="form-group textFindAjax field-<?= $model->attribute ?>">
    <input type="text" id="<?= $id ?>" value="<?= $value ?>" class="hidden form-control" name="<?= $model->getClassName() ?>[<?= $model->attribute ?>]">
    <input autocomplete="off" type="text" id="<?= $showid ?>" class="form-control" placeholder="<?= $placeholder ?>"  value="<?= $value ?>">
    <div class="box-searchResults box-searchResults-<?= $id ?>">
        <div id="searchResults<?= $id ?>" class="search-results">
        </div>
    </div>
</div>
<script type="text/javascript">
    var isAppend<?= $model->attribute ?> = false;
    var keyWordTemp<?= $model->attribute ?> = "";
    var searchTimeout<?= $model->attribute ?>;

    jQuery(document).on("click", function (e) {
        jQuery("#searchResults<?= $id ?>").fadeOut(200);
    });
    jQuery("#<?= $showid ?>").on("keyup", function () {
        var keyword = jQuery.trim(jQuery(this).val());
        if (keyword && keyword != keyWordTemp<?= $model->attribute ?>) {
            search<?= $model->attribute ?>();
        } else if (!keyword) {
            jQuery("#searchResults<?= $id ?>").fadeOut(200);
        }
    });
    function search<?= $model->attribute ?>() {
        if($("#<?= $showid ?>").attr("data-load") != "0") {
            var url = "<?= \yii\helpers\Url::to(["/site/load-text-find-ajax"]) ?>";
            $("#<?= $showid ?>").attr("data-load", 0);
            searchTimeout<?= $model->attribute ?> = setTimeout( function() {
                $("#searchResults<?= $id ?>").fadeIn(200);
                keyword = jQuery.trim($("#<?= $showid ?>").val());
                isAppend<?= $model->attribute ?> = false;
                if (keyword && keyword != keyWordTemp<?= $model->attribute ?>) {
                    keyWordTemp<?= $model->attribute ?> = keyword;
                    loadAjax(url, {id : "<?= $id ?>", keyword : keyword}, $("#searchResults<?= $id ?>"));
                } else if (!keyword) {
                    jQuery("#searchResults<?= $id ?>").fadeOut(200);
                }
                $("#<?= $showid ?>").attr("data-load", 1);
            }, 500);
        }
    }
</script>
<script type="text/javascript">
    count_up_down = 0;
    val_fous_up_down = '';
    text_fous_up_down = '';
    text_fous_up_down_start = $('#<?= $id.'show' ?>').val();
    $(window).click(function(e) {
        if(e.target.id != "<?= $id ?>show") {
            document.removeEventListener("keydown", checkKey<?= str_replace('-', '_', $id)  ?>, false);
        } else {
            document.addEventListener("keydown", checkKey<?= str_replace('-', '_', $id)  ?>);
        }
    });
    function checkKey<?= str_replace('-', '_', $id)  ?>(e) {
        e = e || window.event;
        if (e.keyCode == '38') {
            count_up_down = getDown('.<?= $id.'-count' ?>', count_up_down);
            $('.<?= $id.'-count' ?>.focus').first().removeClass('focus');
            var focus = $('.<?= $id ?>'+count_up_down).first();
            focus.addClass('focus');
            val_fous_up_down = focus.attr('data');
            text_fous_up_down = focus.html();
        }
        else if (e.keyCode == '40') {
            // down arrows
            count_up_down = getUp('.<?= $id.'-count' ?>', count_up_down);
            $('.<?= $id.'-count' ?>.focus').first().removeClass('focus');
            var focus = $('.<?= $id ?>'+count_up_down).first();
            focus.addClass('focus');
            val_fous_up_down = focus.attr('data');
            text_fous_up_down = focus.html();
            // alert('down');
        }
        else if (e.keyCode == '13') {
            count_up_down = 0;
            if(text_fous_up_down_start == $('#<?= $id.'show' ?>').val()) {
                $('#<?= $id.'show' ?>').val(text_fous_up_down);
                $('#<?= $id ?>').val(val_fous_up_down);
            }
            setTimeout(function () {
                document.addEventListener("keydown", checkKey<?= str_replace('-', '_', $id)  ?>);
            }, 100);
            setTimeout(function () {
                jQuery("#searchResults<?= $id ?>").fadeOut(50);
            }, 700);
        }
        console.log(count_up_down);
    }
</script>
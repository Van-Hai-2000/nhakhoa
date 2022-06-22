<?php 
$attribute = $model->attribute;
$show = isset($options['show']) ? $options['show'] : $model->model->$attribute;
$id = (isset($options['id']) && $options['id']) ? $options['id'] : 'find-ajax-'.$model->attribute;
$showid = $id.'show';
?>

<script type="text/javascript">
    $(document).ready(function () {
        var div = $("#<?= $id ?>").parent();
        $("#<?= $id ?>").css("display", "none");
        div.append('<input autocomplete="off" type="text" id="<?= $showid ?>" class="form-control" placeholder="<?= $options['placeholder'] ?>" value="<?= $show ?>"><div class="box-searchResults box-searchResults-<?= $id ?>"> <div id="searchResults<?= $id ?>" class="search-results"> </div> </div>');
        div.find(".help-block").remove();
        div.append('<div class="help-block"></div>');
        $("#<?= $showid ?>").change(function () {
            $("#<?= $id ?>").val("");
            $("#<?= $showid ?>").val("");
        })
    });
    var isAppend<?= $model->attribute ?> = false;
    var keyWordTemp<?= $model->attribute ?> = "x";
    jQuery(document).on("click", function (e) {
        jQuery("#searchResults<?= $id ?>").fadeOut(200);
    });
    jQuery(document).on("click", "#<?= $showid ?>", function () {
        var keyword = jQuery.trim(jQuery(this).val());
        search<?= $model->attribute ?>();
    });
    jQuery(document).on("keyup", "#<?= $showid ?>", function () {
        $('#<?= $id ?>').val(0);
        $(this).val('');
        search<?= $model->attribute ?>();
    });
    function search<?= $model->attribute ?>() {
        if($("#<?= $showid ?>").attr("data-load") != "0") {
            var url = "<?= \yii\helpers\Url::to(["/site/load-text-find-ajax-rank"]) ?>";
            $("#<?= $showid ?>").attr("data-load", 0);
            setTimeout( function() {
                $("#searchResults<?= $id ?>").fadeIn(200);
                keyword = jQuery.trim($("#<?= $id ?>").val());
                isAppend<?= $model->attribute ?> = false;
                // if (keyword != keyWordTemp<?= $model->attribute ?>) {
                    keyWordTemp<?= $model->attribute ?> = keyword;
                    loadAjax(url, {id : "<?= $id ?>", keyword : keyword <?= isset($options['object_id']) ? ', object_id:'.$options['object_id'] : '' ?>}, $("#searchResults<?= $id ?>"));
                // }
                $("#<?= $showid ?>").attr("data-load", 1);
            }, 500);
        }
    }

    function serchtg() {
        search<?= $model->attribute ?>();
    }
</script>
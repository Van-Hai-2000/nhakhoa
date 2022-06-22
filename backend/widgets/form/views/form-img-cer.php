<?php

use yii\helpers\Html;

echo isset($script) ? $script : '';
echo isset($css) ? $css : '';
?>
<style type="text/css">
    .btn-imgs {
        float: right;
        margin: 10px;
    }

    .box-img-uplad {
        margin: auto !important;
        display: inline-block;
        max-width: 100px;
        max-height: 100px;
        overflow: hidden;
        vertical-align: top;
    }

    .box-img-uplad img {
        width: 100%;
    }

    .item-input-form .nice-select {
        width: 100%;
    }
</style>
<div class="item-input-form">
    <div class="img-form">
        <input type="hidden" name="<?= $id ?>" value="<?= $value ?>" id="<?= 'imgf-' . $id ?>">
        <div id="avatar_img_<?= $id ?>" class="box-img-uplad">
            <?php if (isset($images) && $images) { ?>
                <img src="<?php echo $images; ?>" />
            <?php } ?>
        </div>
        <div id="avatar_form_<?= $id ?>" class="btn-imgs" style="display: inline-block;">
            <?= Html::button(Yii::t('app', 'add_image_1'), ['class' => 'btn']); ?>
        </div>
        <?php if ($id == 'certificate4') : ?>
            <div>
                <label for="" style="background: #fff">Nhập link truy xuất</label>
                <input type="text" id="certificate" class="form-control" name="link_certificate" value="<?= ($link_certificate) ? $link_certificate : '' ?>" placeholder="Link truy xuất">
                <select name="code_certificate" id="code-certificate">
                    <option value="">Chọn sản phẩm kết nối</option>

                    <?php
                    $options = \common\models\product\CerXtsShop::getOptionsCodeDiary($model->shop_id);
                    $code = \common\models\product\CertificateProductItem::getCode($model->id, 4);
                    if ($options) foreach ($options as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= $code == $key ? 'selected' : '' ?>><?= $value ?></option>
                    <?php
                    } ?>
                </select>
            </div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        jQuery('#avatar_form_<?= $id ?>').ajaxUpload({
            url: '<?= $url; ?>',
            name: 'file',
            onSubmit: function() {},
            onComplete: function(result) {
                var obj = $.parseJSON(result);
                if (obj.status == '200') {
                    if (obj.data.realurl) {
                        jQuery('#imgf-<?= $id ?>').val(obj.data.avatar);
                        if (jQuery('#avatar_img_<?= $id ?> img').attr('src')) {
                            jQuery('#avatar_img_<?= $id ?> img').attr('src', obj.data.realurl);
                        } else {
                            jQuery('#avatar_img_<?= $id ?>').append('<img src="' + obj.data.realurl + '" />');
                        }
                        jQuery('#avatar_img_<?= $id ?>').css({
                            "margin-right": "10px"
                        });
                    }
                }
            }
        });
    });
</script>
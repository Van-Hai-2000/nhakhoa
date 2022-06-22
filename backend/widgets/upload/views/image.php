<?php

use yii\helpers\Url;
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/uploadify/upload.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl ?>js/upload/uploadify/uploadify.css">
<script src="<?php echo Yii::$app->homeUrl ?>js/upload/uploadify/jquery.uploadify.min.js"></script>
<div class='boxuploadfile'>
    <input id="<?php echo ($id) ? $id : 'uploadfile'; ?>" type="file" name="<?php echo ($id) ? $id . '_name' : 'fileupload' ?>" />
    <div class='valuebox hidden2 <?php echo ($displayvaluebox) ? '' : 'hidden' ?>'>
        <ul class='ulvalbox clearfix'>
        </ul>
    </div>
</div>
<script>
    $(document).ready(function () {
        upload_config.setMaxqueuesize(<?php echo $limit; ?>, "<?php echo ($id) ? $id : 'uploadfile'; ?>");
        upload_config.setQueuesize(0, "<?php echo ($id) ? $id : 'uploadfile'; ?>");
        setTimeout(function () {
            $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').uploadify({
                'buttonText': '<?php echo ($buttontext) ? $buttontext : 'Upload'; ?>',
                'fileSizeLimit': '<?php echo $fileSizeLimit; ?>',
                'width': '<?php echo $buttonwidth; ?>',
                'height': '<?php echo $buttonheight; ?>',
                'method': 'post',
                'multi':<?= ($multi) ? 'true' : 'false'; ?>,
                'formData': {
                    'type': '<?php echo $type; ?>',
                    'path': '<?php echo json_encode($path); ?>',
                    'key': '<?php echo md5($key); ?>',
                    'imageoptions': '<?php echo json_encode($imageoptions); ?>'
                },
                'swf': '<?php echo Yii::$app->homeUrl . 'js/upload/uploadify/uploadify.swf' ?>',
                'fileTypeExts': '*.gif;*.jpg;*.png;*.jpeg;*.bmp;*.ico',
                'onUploadStart': function (file) {
                    if (upload_config.getQueuesize('<?php echo ($id) ? $id : 'uploadfile'; ?>') >= upload_config.getMaxqueuesize('<?php echo ($id) ? $id : 'uploadfile'; ?>')) {
                        $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').uploadify('disable', true);
                        $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').uploadify('cancel', '*');
                        return false;
                    }
                },
                'onUploadSuccess': function (file, data, response) {
                    var da = JSON.parse(data);
                    if (da.code == 200 || da.code == '200') {
                        upload_config.setQueuesize(upload_config.getQueuesize('<?php echo ($id) ? $id : 'uploadfile'; ?>') + 1, '<?php echo ($id) ? $id : 'uploadfile'; ?>');
                        var boxfileupload = $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').closest('.boxuploadfile');
                        var valuebox = boxfileupload.find('.valuebox');
                        var ulvalbox = boxfileupload.find('.valuebox .ulvalbox');
                        valuebox.removeClass('hidden2');
                        ulvalbox.fadeIn(200, function () {
                            ulvalbox.append('<li class="livalbox"><div class="ldvalbox"><i class="removeval" onclick="$(this).parents(\'.livalbox\').remove();upload_config.reduceQueuesize(\'<?php echo ($id) ? $id : 'uploadfile'; ?>\');">x</i><div class="fileimg"><img src="' + da.imgurl + '" /></div><input type="hidden" name="<?php echo ($id) ? $id : 'fileupload' ?>_val[]" class="filevalue" value=\'' + da.imgid + '\'/></div></li>');
                        });
                    } else {
                        alert(da.message);
                    }
<?= $oncecomplete; ?>
                    $('#' + file.id).remove();
                    //Check xem đã vượt quá maxqueuesize chưa
                    if (upload_config.getQueuesize('<?php echo ($id) ? $id : 'uploadfile'; ?>') >= upload_config.getMaxqueuesize('<?php echo ($id) ? $id : 'uploadfile'; ?>')) {
                        $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').uploadify('disable', true);
                        $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').uploadify('cancel', '*');
                        return false;
                    }
                },
                'onQueueComplete': function (queueData) {
<?= $queuecomplete; ?>
                },
                'uploader': '<?= Url::to(['/media/upload/uploadimage']) ?>'
            });
        }, 10);
    });
</script>
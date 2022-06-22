<?php

use yii\helpers\Url;
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/uploadnew/js/upload.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/uploadnew/js/jquery.ui.widget.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/uploadnew/js/jquery.fileupload.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/uploadnew/js/jquery.fileupload-process.js"></script>
<script src="<?php echo Yii::$app->homeUrl ?>js/uploadnew/js/jquery.fileupload-validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->homeUrl ?>js/uploadnew/css/jquery.fileupload.css" />
<div class="box-upload boxuploadfile" id="<?php echo $boxId; ?>">
    <?=
    $this->render('button_' . $buttonStyle, array(
        'boxId' => $boxId,
        'type' => $type,
        'id' => $id,
        'name' => $name,
        'key' => $key,
        'limit' => $limit,
        'buttonwidth' => $buttonwidth,
        'buttonheight' => $buttonheight,
        'multi' => $multi,
        'buttontext' => $buttontext,
        'displayvaluebox' => $displayvaluebox,
        'fileSizeLimit' => $fileSizeLimit,
        'uploader' => $uploader,
        'buttonClass' => $buttonClass
    ));
    ?>
    <div class="box-value">
    </div>
</div>
<script>
    var bookUploadHander = {action: 0, files: []};
    bookUploadHander.addFile = function (file) {
        var fileID = bookUploadHander.guid();
        file.id = fileID;
        bookUploadHander.files[fileID] = file;
        return fileID;
    };
    bookUploadHander.buildTemplate = function (file) {

        var processBar = '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div></div>';
        var template = '<div id="' + file.id + '" class="box-file-item"><div class="file-item-name">' + file.name + '<i class="box-up-wait icon icon-spinner icon-spin bigger-150"></i></div>' + processBar + '</div>';
        return template;
    };
    bookUploadHander.guid = function () {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                    .toString(16)
                    .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
    };
<?php if ($application == 'frontend') { ?>
        //
        function agree() {
            $('.box-upload').addClass('agree');
            modalBox.hide();
            $('#<?php echo $id ?>').trigger('click');
        }
        //
        function deny() {
            modalBox.hide();
        }
        //
        function checkAccess(obj) {
            if (!$(obj).prop('checked')) {
                $('#btnAgree').attr('disabled', 'disabled');
            } else {
                $('#btnAgree').removeAttr('disabled');
            }
        }
<?php } ?>
    $(document).ready(function () {
        upload_config.setMaxqueuesize(<?php echo $limit; ?>, "<?php echo ($id) ? $id : 'uploadfile'; ?>");
        upload_config.setQueuesize(0, "<?php echo ($id) ? $id : 'uploadfile'; ?>");
<?php if ($application == 'frontend') { ?>
            $('.box-upload').on('click', function () {
                var _thi = $(this);
                if (_thi.hasClass('agree')) {
                    return true;
                } else {
                    modalBox.reset();
                    modalBox.setMoreClass('altbox');
                    modalBox.setBody(<?php echo json_encode($this->render('clause', array(), true)); ?>);
                    modalBox.show();
                    return false;
                }
            });
<?php } ?>
        $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').fileupload({
            url: '<?php echo $uploader; ?>',
            type: 'POST',
            formData: {
                'type': '<?php echo $type; ?>',
                'path': '<?php echo json_encode($path); ?>',
                'key': '<?php echo md5($key); ?>',
                'imageoptions': '<?php echo json_encode($imageoptions); ?>',
                'name': '<?php echo ($name) ? $name : 'files' ?>'
            },
            dataType: 'json',
            autoUpload: true,
            maxNumberOfFiles: <?php echo $limit ?>,
            acceptFileTypes: /(\.|\/)(gif|jpg|png|jpeg|bmp|ico)$/i,
            maxFileSize: <?php echo $fileSizeLimit; ?>
        }).on('fileuploadsubmit', function (e) {
        }).on('fileuploadadd', function (e, data) {
            $.each(data.files, function (index, file) {
                bookUploadHander.addFile(file);
                var template = bookUploadHander.buildTemplate(file);
                jQuery('#<?php echo $boxId; ?> .box-value').prepend(template);
            });
            bookUploadHander.action++;
        }).on('fileuploadprogress', function (e, data) {
            var file = data.files[0];
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#' + file.id + ' .progress-bar').css(
                    'width',
                    progress + '%'
                    );
            $('#' + file.id + ' .progress-bar').text(progress + '%');
        }).on('fileuploadprocessalways', function (e, data) {
            var file = data.files[0];
            if (file.error) {
                console.log(file);
                $('#' + file.id + ' .progress').hide();
                $('#' + file.id).addClass('bg-danger');
                $('#' + file.id + ' .box-up-wait').hide();
                if(file.size > 2048000) {
                   $('#' + file.id).append('<span class="eror-img">Tải dung lượng ảnh không hợp. '+Math.round((file.size/1024000) * 100)/100+'MB > 2MB</span>');
                }
                
            }
        }).on('fileuploaddone', function (e, data) {
            var file = data.files[0];
            if (file) {
                var da = data.result;
                if (da.code == 200) {
                    if (da.form) {
                        $('#' + file.id).append(da.form);
                    }
                    $('#' + file.id).addClass('success');
                    upload_config.setQueuesize(upload_config.getQueuesize('<?php echo ($id) ? $id : 'uploadfile'; ?>') + 1, '<?php echo ($id) ? $id : 'uploadfile'; ?>');
                    var boxfileupload = $('#<?php echo ($id) ? $id : 'uploadfile'; ?>').closest('.boxuploadfile');
                    var valuebox = boxfileupload.find('.valuebox');
                    var ulvalbox = boxfileupload.find('.valuebox .ulvalbox');
                    valuebox.removeClass('hidden2');
                    ulvalbox.fadeIn(200, function () {
                        ulvalbox.append('<li class="livalbox"><div class="ldvalbox"><i class="removeval" onclick="$(this).parents(\'.livalbox\').remove();upload_config.reduceQueuesize(\'<?php echo ($id) ? $id : 'uploadfile'; ?>\');">x</i><div class="fileimg"><img src="' + da.imgurl + '" /></div><input type="hidden" name="<?php echo ($id) ? $id : 'fileupload' ?>_val[]" class="filevalue" value=\'' + da.imgid + '\'/></div></li>');
                    });
                    $('#' + file.id).hide();
                } else {
                    if (da.message) {
                        $('#' + file.id).addClass('bg-danger');
                        $('#' + file.id).append(da.message);
                        $('#' + file.id + ' .progress').hide();
                    }
                }
                <?php echo $oncecomplete; ?>
            }
            //
        }).on('fileuploadfail', function (e, data) {
            console.log(data);
            $.each(data.files, function (index, file) {
                $('#' + file.id).append('<p class="text-danger">Upload errors</p>');
                $('#' + file.id + ' .box-up-wait').hide();
            });
        });
    });
</script>
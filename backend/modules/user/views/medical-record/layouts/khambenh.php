<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/7/2021
 * Time: 8:53 AM
 */

use yii\widgets\ActiveForm;

?>
<div class="modal fade khambenh" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?php $form = ActiveForm::begin([
            'id' => 'kbsm'
    ]); ?>

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Chọn thủ thuật điều trị</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="box-append-cat">
                    <div class="row prd-add" style="margin-bottom: 15px">
                        <div class="col-md-4">
                            <select id="medicalrecord-branch" class="form-control branch" name="branch" required>
                                <option value="">Chọn chi nhánh</option>
                                <?php if ($branchs): ?>
                                    <?php foreach ($branchs as $k => $branch): ?>
                                        <option value="<?= $k ?>" <?= isset($user_admin) && $user_admin && $user_admin->branch_id == $k ? 'selected' : '' ?>><?= $branch ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input name="time-create" type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', time()) ?>">
                        </div>
                    </div>
                </div>
                <a class="add-select-cat"><i class="glyphicon glyphicon-plus"></i> Thêm thủ thuật</a>

                <?=
                /**
                 * Banner main
                 */
                backend\widgets\upload\UploadWidget::widget([
                    'type' => 'images',
                    'id' => 'imageupload',
                    'buttonheight' => 25,
                    'path' => array('medical-record'),
                    'limit' => 100,
                    'multi' => true,
                    'imageoptions' => array(
                        'resizes' => array(array(300, 300))
                    ),
                    'buttontext' => 'Thêm ảnh',
                    'displayvaluebox' => false,
                    'oncecomplete' => "callbackcomplete(da);",
                    'onUploadStart' => 'ta=false;',
                    'queuecomplete' => 'ta=true;',
                ]);
                ?>
                <div class="row" id="wrap_image_album">
                </div>
                <div class="help-block"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary">Xác nhận</button>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

    function callbackcomplete(data) {
        var html = '<div class="col-md-55">';
        html += '<div class="thumbnail">';
        html += '<div class="image view view-first">';
        html += '<img id="img-up-' + data.imgid + '" style="display: block;" src="' + data.imgurl + '" />';
        html += '<input type="hidden" value="' + data.imgid + '" name="newimage[]" class="newimage" />';
        html += '<div class="mask">';
        html += '<p>&nbsp;</p>';
        html += '<div class="tools tools-bottom">';
        html += ' <a onclick="cropimages(this, \'' + data.imgurl + '\',\'' + data.imgid + '\')" href="javascript:void(0)" title="Chỉnh sửa ảnh này"><i class="fa fa-crop"></i></a>';
        html += '<a onclick="deleteNewImage(this, \'col-md-55\')" href="javascript:void(0)" title="Xóa ảnh này"><i class="fa fa-times"></i></a>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="caption">';

        html += '<div class="radio">';
        html += '<label>';
        html += '<input type="radio" value="new_' + data.imgid + '" name="setava" /> Đặt làm ảnh đại diện';
        html += '</label>';
        html += '</div>';

        html += '</div>';
        html += '</div>';
        html += '</div>';

        jQuery('#wrap_image_album').append(html);
    }

    function deleteNewImage(_this, wrap) {
        if (confirm('Bạn có chắc muốn xóa ảnh?')) {
            $(_this).closest('.' + wrap).remove();
        }
        return false;
    }

    $.postJSON = function (url, data, func) {
        $.post(url + (url.indexOf("?") == -1 ? "?" : "&") + "callback=?", data, func, "json");
    };

    function deleteOldImage(_this, wrap, id) {
        if (confirm('Bạn có chắc muốn xóa ảnh?')) {
            $.getJSON(
                "<?= \yii\helpers\Url::to(['/medicine/medicine/delete-image']) ?>",
                {id: id}
            ).done(function (data) {
                $(_this).closest('.' + wrap).remove();
            }).fail(function (jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });
        }
        return false;
    }
</script>

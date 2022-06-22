<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/7/2021
 * Time: 3:12 PM
 */

?>
<div class="modal fade lieutrinh" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Liệu trình điều trị</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body body_list_plan">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    //load lịch sử đặt xưởng
    function load_plan() {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['load-plan', 'id' => $model->id]) ?>',
            type: 'GET',
            data: {},
            success: function (data) {
                $('.body_list_plan').empty().html(data)
            }
        })
    }

</script>

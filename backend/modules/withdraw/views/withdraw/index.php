<?php

use yii\helpers\Url;

$item = new \common\models\gcacoin\WithDraw();
?>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Quản lý rút tiền</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="w0" class="grid-view">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tài khoản</th>
                                        <th>Số Vr</th>
                                        <th>Số tiền tương ứng</th>
                                        <th>Chủ thẻ</th>
                                        <th>Số tài khoản</th>
                                        <th>Ngân hàng</th>
                                        <th>Ngày yêu cầu</th>
                                        <th class="action-column" style="width: 6%">Xác nhận</th>
                                        <th class="action-column" style="width: 6%">Hủy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($model as $tg) :
                                        $item->setAttributeShow($tg);
                                    ?>
                                        <tr data-key="2">
                                            <td><?= $tg['user_id'] ?></td>
                                            <td><?= $tg['username'] ?></td>
                                            <td><?= $item->show('value'); ?></td>
                                            <td><?= formatMoney($item->getMoney())  ?> đ</td>
                                            <td><?= $item->getBankInfo('name') ?></td>
                                            <td><?= $item->getBankInfo('number') ?></td>
                                            <td><?= $item->getBankInfo('bank_name') ?></td>
                                            <td><?= date('d-m-Y', $item['created_at']) ?></td>
                                            <td align="center">
                                                <a href="<?= Url::to(['withdraw/confirm', 'id' => $item['id']]) ?>" title="Sửa" aria-label="Xác nhận" class="btn btn-success">Xác nhận</a>
                                            </td>
                                            <td align="center">
                                                <a href="#" onclick="cancel(this)" data-user-id="<?= $item['user_id'] ?>" data-id="<?= $item['id'] ?>" title="Sửa" aria-label="Xác nhận" class="btn btn-danger">Hủy</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function cancel(t) {
        var user_id = $(t).data('user-id');
        var id = $(t).data('id');
        var body = prompt('Nhập lý do hủy yêu cầu rút tiền.');
        if (body != null) {
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['/withdraw/withdraw/confirm-cancel']) ?>',
                type: 'POST',
                data: {
                    user_id: user_id,
                    body: body,
                    id: id
                },
                success: function(dt) {
                    var data = JSON.parse(dt);
                    if (data.success) {
                        alert('Hủy yêu cầu thành công.');
                        window.location.reload();
                    } else {
                        alert(data.errors);
                    }
                }
            })
        }
    }
</script>
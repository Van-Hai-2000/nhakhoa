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
                        <h2>Lịch sử rút tiền</h2>
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
                                        <th>Số tài khoản</th>
                                        <th>Ngân hàng</th>
                                        <th>Ngày phê duyệt</th>
                                        <th class="action-column" style="width: 6%">Xem chi tiết</th>
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
                                            <td><?= $item->getBankInfo('number') ?></td>
                                            <td><?= $item->getBankInfo('bank_name') ?></td>
                                            <td><?= date('h:i:s d-m-Y', $item['updated_at']) ?></td>
                                            <td align="center">
                                                <a href="<?= Url::to(['withdraw/view', 'id' => $item['id']]) ?>" title="Xem chi tiết" aria-label="Xem chi tiết" class="btn btn-success">Xem chi tiết</a>
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
<?php
use yii\helpers\Url;
?>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Cấu hình V</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="w0" class="grid-view">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>(%)giảm giá khi thanh toán bằng PGA V</th>
                                    <th>Tỉ lệ quy đổi tiền/V</th>
                                    <th class="action-column" style="width: 6%">Sửa</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($model as $item): ?>
                                    <tr data-key="2">
                                        <td><?= $item->sale ?>%</td>
                                        <td><?= number_format($item->money) ?>/<?= number_format($item->gcacoin) ?></td>
                                        <td align="center">
                                            <a href="<?= Url::to(['gcacoin/update','id' => $item->id]) ?>" title="Sửa"
                                               aria-label="Sửa" data-pjax="0"><span
                                                        class="glyphicon glyphicon-pencil"></span></a>
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
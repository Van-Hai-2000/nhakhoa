<?php

use yii\helpers\Url;

?>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Quản lý Chat</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="w0" class="grid-view">
                            <div class="summary">Trình bày <b>1-1</b> trong số <b>1</b> mục.</div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Người dùng 1</th>
                                    <th>Người dùng 2</th>
                                    <th class="action-column" style="width: 6%">Xem chi tiết</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($dt as $item): ?>
                                    <tr data-key="2">
                                        <td><?= $item->name1 ?></td>
                                        <td><?= $item->name2 ?></td>
                                        <td align="center">
                                            <a href="<?= Url::to(['chat/detail', 'user_id1' => $item->user_id1, 'user_id2' => $item->user_id2]) ?>" title="Xem chi tiết">
                                                <?php if ($item->status == 0): ?>
                                                    <span class="glyphicon glyphicon-eye-open" style="color: red"></span>
                                                <?php else: ?>
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                <?php endif; ?>
                                            </a>
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
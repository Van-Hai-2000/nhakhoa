<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 1/7/2022
 * Time: 3:17 PM
 */
?>
<?php if ($logs): ?>
    <div class="container">
        <div id="timeline">
            <?php foreach ($logs as $log): ?>
                <div class="row timeline-movement">
                    <div class="col-md-12">
                        <h4 class="label_user"><?= $log->userAdmin->fullname ?> (<?= $log->userAdmin->id ?>)
                            - <?= isset($log->branch->name) && $log->branch->name ? $log->branch->name : '' ?>
                            - <?= date('d/m/Y H:i:s', $log->created_at) ?></h4>
                        <h4 class="label_action"><?= $log->action ?></h4>
                    </div>
                    <div class="col-sm-6  timeline-item">
                        <div class="row">
                            <div class="col-sm-11">
                                <div class="timeline-panel credits">
                                    <ul class="timeline-panel-ul">
                                        <li><span class="importo">Dữ liệu trước thay đổi</span></li>
                                        <li>
                                            <span class="causale"><?= \common\components\ClaNhakhoa::getContentLog($log->record_before) ?></span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6  timeline-item">
                        <div class="row">
                            <div class="col-sm-offset-1 col-sm-11">
                                <div class="timeline-panel debits">
                                    <ul class="timeline-panel-ul">
                                        <li><span class="importo">Dữ liệu sau thay đổi</span></li>
                                        <li>
                                            <span class="causale"><?= \common\components\ClaNhakhoa::getContentLog($log->record_after) ?></span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
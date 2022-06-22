<?php

use common\models\order\OrderLog;
use common\models\order\Order;

$logs = OrderLog::getLogsByOrderId($model->id);
?>
<?php
$appear = ['name', 'phone', 'address', 'facebook'];
$array_format = ['money_customer_transfer', 'shipfee', 'order_total'];
foreach ($logs as $log) {
    $content = json_decode($log['content'], true);
    $user = \backend\models\UserAdmin::findOne($log['user_id']);
    ?>
    <div class="item-log">
        <b>- <?= $user['username'] ?></b>: <?= date('H:i d/m/Y', $log['created_at']) ?>
        <ul>
            <?php
            foreach ($content as $key => $changes) {
                ?>
                <li><b><i><?= $model->getAttributeLabel($key) ?></i></b>: 
                    <?php
                    if (in_array($key, $appear)) {
                        echo $changes['old'] . ' ----> ' . $changes['new'];
                    } else if (in_array($key, $array_format)) {
                        $changes['old'] = $changes['old'] ? ((int) $changes['old']) : 0;
                        $changes['new'] = $changes['new'] ? ((int) $changes['new']) : 0;
                        echo number_format($changes['old'], 0, ',', '.') . ' ----> ' . number_format($changes['new'], 0, ',', '.');
                        if ($key == 'order_total') {
                            echo ' ----> <b><i>Đồng bộ giá</i></b>';
                        }
                    } else if ($key == 'status') {
                        // Trạng thái đơn hàng
                        echo Order::getNameStatus($changes['old']) . ' ----> ' . Order::getNameStatus($changes['new']);
                    } else if ($key == 'confirm_customer_transfer') {
                        // Đã nhận chuyển khoản
                        echo Order::getPaymentStatusName($changes['old']) . ' ----> ' . Order::getPaymentStatusName($changes['new']);
                    } else if ($key == 'bank_transfer') {
                        // Ngân hàng chuyển khoản
                        echo Order::getNameBankTransfer($changes['old']) . ' ----> ' . Order::getNameBankTransfer($changes['new']);
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
}
?>

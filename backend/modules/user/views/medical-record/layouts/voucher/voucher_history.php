<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/9/2021
 * Time: 9:56 AM
 */
?>

<?php if (isset($factory) && $factory): ?>
    <?php foreach ($factory as $value): ?>
        <tr class="vc">
            <td><?= $value->voucher->voucher ?></td>
            <td><?= \common\models\voucher\Voucher::getType()[$value->type] ?></td>
            <td><?= number_format($value->type_value) ?></td>
            <td><?= number_format($value->total_money) ?></td>
            <td><?= isset($value->branch->name) && $value->branch->name ? $value->branch->name : 'Tất cả' ?></td>
            <td>
                <button type="button" class="btn btn-primary" onclick="delete_voucher(this)" data-url="<?= \yii\helpers\Url::to(['delete-voucher', 'id' => $value->id]) ?>">Xóa</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
<script>
    function delete_voucher(t) {
        if (confirm('Bạn chắc chắn muốn xóa mã giảm giá này')) {
            $.ajax({
                url: $(t).data('url'),
                type: 'GET',
                data: {},
                success: function (data) {
                    data = JSON.parse(data);
                    if(data.success){
                        $(t).parents('.vc').remove();
                    }else{
                        alert(data.message);
                    }
                }
            })
        }
    }
</script>
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
        <tr>
            <td><?= date('d-m-Y', $value['created_at']) ?></td>
            <td><?= isset($value['time_return']) && $value['time_return'] ? date('d-m-Y', $value['time_return']) : 'Chờ xưởng xác nhận' ?></td>
            <td><?= $value['userAdmin']['fullname'] ?></td>
            <td><?= number_format($value['money']) ?></td>
            <td><?= $value['quantity'] ?></td>
            <td><?= isset($value['loaimau']['name']) && $value['loaimau']['name'] ? $value['loaimau']['name'] : '' ?></td>
            <td><?= $value['branch']['name'] ?></td>
            <td><?= $value['insurance_code'] ?></td>
            <td>
                <a href="javascript:void(0)"
                   title="Sửa" aria-label="Sửa" onclick="edit_factory(<?= $value['id'] ?>,'<?= date('Y-m-d\TH:i', $value['created_at']) ?>')">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a href="<?= \yii\helpers\Url::to(['delete-factory', 'id' => $value['id']]) ?>"
                   title="Xóa" aria-label="Xóa" data-pjax="0"
                   data-confirm="Bạn có chắc là sẽ xóa mục này không?" data-method="post">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

    <script>
        function edit_factory(f_id,time_create) {
            var factoryList = {};
            factoryList = <?= json_encode($factory) ?>;
            $.each( factoryList, function( key, value ) {
                if(value.id == f_id){
                    console.log(value);
                    $('#f_id').val(value.id);
                    $('#factory_branch').val(value.branch.id);
                    $('#factory_id').val(value.factory_id);
                    $('#factory_device_id').val(value.device_id);
                    $('#factory_quantity').val(value.quantity);
                    $('#factory_admin_id').val(value.admin_id);
                    $('#factory_admin_id').trigger('change');
                    $('#factory_phone').val(value.phone);
                    $('#factory_time_create').val(time_create);
                }
            });
        }
    </script>
<?php endif; ?>

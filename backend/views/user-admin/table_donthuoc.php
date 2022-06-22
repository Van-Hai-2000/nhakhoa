<?php if ($medical_record_item_medicine): ?>
    <?php foreach ($medical_record_item_medicine as $value): ?>
        <tr class="even pointer">
            <td>
                <?= $value->medical_record_id ?></td>
            <td>
                <?= isset($value->created_at) && $value->created_at ? date('d-m-Y', $value->created_at) : '' ?></td>
            <td>
                <?= isset($value->created_at) && $value->created_at ? date('H:i:s', $value->created_at) : "" ?></td>
            <td>
                <?= isset($value->userAdmin->username) && $value->userAdmin->username ? $value->userAdmin->fullname : '' ?></td>
            <td>
                <?= isset($value->medicine->name) && $value->medicine->name ? $value->medicine->name : '' ?></td>
            <td>
                <?= isset($value->medicine->price) && $value->medicine->price ? number_format($value->medicine->price) : "" ?></td>
            <td>
                <?= isset($value->quantity) && $value->quantity ? $value->quantity : "" ?></td>
            <td>
                <?= isset($value->medicine) && $value->medicine ? number_format($value->quantity * $value->medicine->price) : "" ?></td>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
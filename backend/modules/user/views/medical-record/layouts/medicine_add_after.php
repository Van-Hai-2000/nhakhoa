<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/8/2021
 * Time: 4:17 PM
 */

?>
<tr data-key="8">
    <td><?= date('d-m-Y',$medical_record_item_medicine->created_at) ?></td>
    <td><?= date('H:i:s',$medical_record_item_medicine->created_at) ?></td>
    <td><?= $medical_record_item_medicine->medicine->name ?></td>
    <td><?= number_format($medical_record_item_medicine->medicine->price) ?></td>
    <td><?= $medical_record_item_medicine->quantity ?></td>
    <td><?= number_format($medical_record_item_medicine->quantity * $medical_record_item_medicine->medicine->price) ?></td>
</tr>

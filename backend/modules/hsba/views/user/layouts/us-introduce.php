<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 12/10/2021
 * Time: 9:39 AM
 */
?>
<div class="form-group field-user-introduce_id">
    <select id="user-introduce_id" class="form-control" name="User[introduce_id]">
        <option value="">Chọn người giới thiệu</option>
        <?php if ($useradmin): ?>
            <?php foreach ($useradmin as $value): ?>
                <option value="<?= $value->id ?>"><?= $value->fullname ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <div class="help-block"></div>
</div>

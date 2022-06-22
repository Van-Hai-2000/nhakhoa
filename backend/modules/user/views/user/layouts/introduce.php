<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 12/10/2021
 * Time: 9:39 AM
 */
?>
<?php if ($type): ?>
    <?php if ($type == 1): ?>
        <div class="form-group field-user-introduce_id">
            <select id="user-introduce_id" class="form-control" name="User[introduce_id]">
                <option value="">Chọn người giới thiệu</option>
                <?php foreach (\backend\models\UserAdmin::getUserIntroduce() as $key => $value): ?>
                    <option value="<?= $key ?>"><?= $value ?></option>
                <?php endforeach; ?>
            </select>
            <div class="help-block"></div>
        </div>
    <?php else: ?>
        <div class="form-group field-user-introduce">
            <input type="hidden" name="User[introduce]" value="">
            <div id="user-introduce" class="user-introduce-custom">
                <label><input class="usintroduce" type="radio" name="User[introduce]" value="1" checked> Cộng tác viên</label>
                <label><input class="usintroduce" type="radio" name="User[introduce]" value="2"> Fanpage</label>
                <label><input class="usintroduce" type="radio" name="User[introduce]" value="3"> Phòng kinh doanh</label>
            </div>
            <div class="help-block"></div>
        </div>
        <script>
            ab(1);
            $('.usintroduce').change(function() {
                var value = $(this).val();
                ab(value);
            });
        </script>
    <?php endif; ?>
<?php endif; ?>
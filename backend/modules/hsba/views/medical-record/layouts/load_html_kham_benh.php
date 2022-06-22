<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 6/6/2022
 * Time: 2:16 PM
 */
?>
<div class="form-group dstt" itemid="<?= $stt ?>" id="dstt_<?= $stt ?>">
    <div class="row" style="margin-bottom: 5px">
        <!--Nhóm thủ thuật-->
        <div class="col-md-2">
            <select id="product_category_<?= $stt ?>"
                    onchange="change_product_cat(<?= $stt ?>,'<?= \yii\helpers\Url::to(['get-product']) ?>')"
                    class="form-control " name="product_category_id[<?= $stt ?>]" required>
                <option value="">Chọn nhóm thủ thuật</option>
                <?php if ($categories): ?>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!--Thủ thuật-->
        <div class="col-md-4">
            <select onchange="change_product(<?= $stt ?>,'<?= \yii\helpers\Url::to(['get-detail-product']) ?>')" id="product_<?= $stt ?>" class="form-control " name="product_id[<?= $stt ?>]" required>
                <option value="">Chọn thủ thuật</option>
            </select>
            <input type="hidden" id="product_price_hidden_<?= $stt ?>" value="0">
        </div>

        <!--Số lượng-->
        <div class="col-md-2">
            <input id="quantity_<?= $stt ?>" onchange="change_quantity(<?= $stt ?>)" type="number" class="form-control" name="quantity[<?= $stt ?>]" placeholder="Số lượng" min="1" required>
        </div>

        <!--Tổng tiền-->
        <div class="col-md-4">
            <input id="total_price_<?= $stt ?>" type="number" class="form-control" name="total_price[<?= $stt ?>]" placeholder="Thành tiền" min="0">
        </div>
    </div>

    <div class="row" style="margin-bottom: 5px">
        <!--Hình thức giảm giá-->
        <div class="col-md-2">
            <select id="hinh_thuc_giam_gia" class="form-control" name="hinh_thuc_giam_gia[<?= $stt ?>]" required>
                <option value="1">Giảm theo %</option>
                <option value="2">Giảm theo tiền mặt</option>
            </select>
        </div>

        <!--Giá trị giảm giá-->
        <div class="col-md-3">
            <input id="gia_tri_giam_gia" type="number" class="form-control" name="gia_tri_giam_gia[<?= $stt ?>]"
                   placeholder="Thành tiền" min="0">
        </div>

        <!--VAT-->
        <div class="col-md-1">
            <input id="vat" type="number" class="form-control" name="vat[<?= $stt ?>]" placeholder="VAT(%)" min="0">
        </div>

        <!--Bác sĩ thực hiện-->
        <div class="col-md-3">
            <select id="doctor_<?= $stt ?>" class="form-control " name="doctor[<?= $stt ?>]" required>
                <option value="">Chọn bác sĩ</option>
                <?php if ($doctor): ?>
                    <?php foreach ($doctor as $k => $doc): ?>
                        <option value="<?= $k ?>"><?= $doc ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!--Chọn sale-->
        <div class="col-md-3">
            <select id="sale_<?= $stt ?>" class="form-control " name="sale[<?= $stt ?>]" required>
                <option value="">Chọn Sale</option>
                <?php if ($users): ?>
                    <?php foreach ($users as $k => $doc): ?>
                        <option value="<?= $k ?>"><?= $doc ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="row" style="margin-bottom: 5px">
        <!--Chọn người chăm sóc lại-->
        <div class="col-md-3">
            <select id="nguoi_cham_soc_<?= $stt ?>" class="form-control " name="nguoi_cham_soc[<?= $stt ?>]" required>
                <option value="">Chọn người chăm sóc lại</option>
                <?php if ($users): ?>
                    <?php foreach ($users as $k => $doc): ?>
                        <option value="<?= $k ?>"><?= $doc ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!--Chọn người hưởng hoa hồng-->
        <div class="col-md-8">
            <select id="team_<?= $stt ?>" class="form-control " name="team[<?= $stt ?>][]" multiple>
                <?php if (isset($users) && $users): ?>
                    <?php foreach ($users as $key => $user): ?>
                        <option value="<?= $key ?>"><?= $user ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-1 medicalrecord-action">
            <span class="gender-team col-md-1" onclick="genderTeamV2(this,<?= $stt ?>)">+</span>
            <span onclick="delete_thu_thuat_kham_benh(<?= $stt ?>)" class="delete-cat col-md-1">x</span>
        </div>
    </div>
    <div class="row" style="margin-bottom: 5px">
        <div class="col-md-12"><input name="prd_note[<?= $stt ?>]" class="form-control" placeholder="Nhập nội dung thủ thuật"/></div>
    </div>
    <div id="dshh_<?= $stt ?>">

    </div>
    <div class="help-block"></div>
</div>

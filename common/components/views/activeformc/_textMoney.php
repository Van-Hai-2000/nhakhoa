<input style="display: none;" id="<?= "money-input-" . $model->attribute ?>-tg" class="form-control" name="<?= $model->getClassName() ?>[<?= $model->attribute ?>]" placeholder="<?= $model->getAttrName() ?>">
<script type="text/javascript">
    $(document).ready(function() {
        $('#<?= "money-input-" . $model->attribute ?>').attr('name', '');
        $('#<?= "money-input-" . $model->attribute ?>').change(function() {
            var text = $(this).val().replace(/\./g, "");
            $('#<?= "money-input-" . $model->attribute ?>-tg').val(text);
        });
        $('#<?= "money-input-" . $model->attribute ?>').change();
    })
</script>
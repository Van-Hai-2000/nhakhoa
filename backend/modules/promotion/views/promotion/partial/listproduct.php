<div class="x_title">
    <a class="btn btn-success click add-new-product"><?= Yii::t('app', 'add_new_product') ?></a>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div id="w0" class="grid-view">
        <?php if($products) { ?>
            <div class="summary"><?=  Yii::t('app', 'count_product') ?> : <?= count($products) ?></div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><?= Yii::t('app', 'product') ?></th>
                        <th class="action-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) { ?>
                        <tr data-key="8">
                            <td><?= $product['product_name'] ?></td>
                            <td>
                                <a class="click remove-promotion" title="Xóa" data-id="<?= $product['id'] ?>" aria-label="Xóa" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.add-new-product').click(function() {
            $('.add-new-product').css('display', 'none');
            jQuery.ajax({
                url: "<?= \yii\helpers\Url::to(['/promotion/promotion/get-product']) ?>",
                beforeSend: function () {
                },
                success: function (res) {
                    $('.box-crop').css('display', 'flex');
                    $('#box-product-add').html(res);
                },
                error: function () {
                }
            });
        });
        $('.remove-promotion').click(function() {
            var _this = $(this);
            if(confirm('<?= Yii::t('app', 'delete_sure') ?>')) {
                var id = $(this).attr('data-id');
                $.getJSON(
                        "<?= \yii\helpers\Url::to(['/promotion/promotion/delete-product']) ?>",
                        {id: id}
                ).done(function (data) {
                    _this.parent().parent().remove();
                }).fail(function (jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error;
                    console.log("Request Failed: " + err);
                });
            }
        });
    });
</script> 
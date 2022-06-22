<?php if($product_add) { ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('app', 'product') ?></th>
                <th class="action-column">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product_add as $product) { ?>
                <tr data-key="8">
                    <td><?= $product['name'] ?></td>
                    <td>
                        <a class="click add-promotion" title="<?= Yii::t('app', 'add') ?>" data-name="<?= $product['name'] ?>" data-id="<?= $product['id'] ?>" aria-label ><i class="fa fa-angle-double-right"></i></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if(isset($count_page) && $count_page > 2) { ?>
        <ul class="pagination">
            <?php for ($i=1; $i <= $count_page; $i++) { ?>
            <li class="lis <?= ($i ==$page) ? 'active' : ''?>"><a class="click product-page" data-page="<?= $i ?>"><?= $i ?></a></li>
            <?php } ?>
        </ul>
    <?php } ?>
<?php } else { ?>
    <p><?= Yii::t('app', 'not_result') ?></p>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.add-promotion').click(function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            var name = _this.attr('data-name');
            $('#input-value').val($('#input-value').val()+','+id);
            _this.parent().parent().remove();
            $('#product-selected').append('<tr data-key="'+id+'"> <td>'+name+'</td> <td> <a class="click remove-promotion-add" title="Xóa" data-id="'+id+'" aria-label ><span class="glyphicon glyphicon-trash"></span></a></td></tr>')
        });
    });

    
</script> 
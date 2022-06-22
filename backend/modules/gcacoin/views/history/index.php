<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\product\searchProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lịch sử PGA V';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            'created_at' => [
                                'header' => Yii::t('app', 'created_time'),
                                'content' => function($model) {
                                    return date('H:i:s d/m/Y', $model->created_at);
                                }
                            ],
                            'user_id',
                            [
                                'attribute' => 'gca_coin',
                                'value' => function($model) {
                                    return $model->getTextCoin($model->gca_coin);
                                }
                            ],
                            [
                                // 'header' => 'Số Dư',
                                'attribute' => 'last_coin',
                                'value' => function($model) {
                                    return $model->getTextCoin($model->last_coin);
                                }
                            ],
                            [
                                'header' => 'Nội dung',
                                'value' => function($model) {
                                    return $model->data;
                                }
                            ],
                            'type_coin',
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function changeStatusProduct(_this, pid, shop_status) {
        var status = $(_this).val();
        if(shop_status == 1 || status != '1') {
            $.getJSON(
                '<?= \yii\helpers\Url::to(['/product/product/change-status']) ?>',
                {status: status, pid: pid},
                function (data) {
                    if (data.code == 200) {
                        alert('Cập nhật trạng thái thành công');
                        location.reload();
                    }
                }
            );
        } else {
            alert('Gian hàng chưa được xác thực. Vui lòng xác thực gian hàng trước khi xác thực sản phẩm.');
            $(_this).val(2);
        }
        
    }
</script>
<script type="text/javascript">
    jQuery(document).on('click', '.box-checkbox', function () {
        if(confirm("<?= Yii::t('app', 'you_sure_change') ?>")) {
            $(this).css('display','none');
            setTimeout(function(){ 
                $('.box-checkbox').css('display','block');
            }, 1000);
            var checkbox = $(this).find('.updateajax').first();
            changeHot(checkbox);
            var label = $(this).find('.switchery').first();
            if(!$(this).hasClass('check')) {
                $(this).addClass('check');
            } else {
                $(this).removeClass('check');
            }
        }
        return false;
    });
    
    function changeHot(_this) {
        var link = _this.attr('data-link');
        if (link) {
            jQuery.ajax({
                type: 'get',
                dataType: 'json',
                url: link,
                data: null,
                success: function(res) {
                    if (res.code == 200) {
                        // _this.html(res.html);
                        _this.attr('data-link',res.link);
                        // _this.attr('title',res.title);
                    } else {
                        alert('<?= Yii::t('app', 'update_fail') ?>');
                    }
                },
                error: function() {
                   alert('<?= Yii::t('app', 'update_fail') ?>');
                }
            });
        }
    }
</script>

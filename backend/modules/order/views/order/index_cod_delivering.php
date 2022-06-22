<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\order\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý đơn hàng';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?php //  Html::a('Tạo đơn hàng', ['create'], ['class' => 'btn btn-success pull-right'])   ?>
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content">


                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                    Danh sách đơn hàng
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_content2" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                    In danh sách
                                </a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                <?=
                                $this->render('partial_cod_delivering/basicinfo', [
                                    'dataProvider' => $dataProvider,
                                    'searchModel' => $searchModel
                                ]);
                                ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="home-tab">
                                <?=
                                $this->render('partial_cod_delivering/printlist', [
                                    'dataProvider' => $dataProvider,
                                    'searchModel' => $searchModel
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('.received_money').click(function () {
            var received_money = 0;
            var order_id = $(this).closest('tr').attr('data-key');
            if ($(this).is(':checked')) {
                received_money = 1;
            }
            $.getJSON(
                    '<?= Url::to(['/order/order/update-received-money']) ?>',
                    {received_money: received_money, order_id: order_id},
                    function (data) {
                        if (data.code == 200) {
                            alert('Cập nhật thành công.');
                            location.reload();
                        }
                    }
            );
        });

        $('.select_user_delivery').change(function () {
            var user_id = $(this).val();
            var order_id = $(this).closest('tr').attr('data-key');
            if (user_id != 0) {
                $.getJSON(
                        '<?= Url::to(['/order/order/update-user-delivery']) ?>',
                        {user_id: user_id, order_id: order_id},
                        function (data) {
                            if (data.code == 200) {
                                alert('Cập nhật thành công.');
                                location.reload();
                            }
                        }
                );
            }
        });
    });
</script>

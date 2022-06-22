<?php

use common\components\ClaHost;

$siteinfo = common\components\ClaLid::getSiteinfo();
?>
<style type="text/css">
    /*.center {
        text-align: center;
    }
    #wrap-print-order-border {
        padding: 15px;
    }*/
    #printable { display: none; }

    @media print
    {
        #table-order{
            border-collapse: collapse;
        }
        #table-order tr, #table-order td{
            border: 1px solid #dddddd;
        }
    }
</style>
<button type="button" id="btnPrint" class="btn btn-lg btn-primary">In đơn hàng</button>
<br />
<br />
<br />
<div id="wrap-print-order">
    <div id="wrap-print-order-border" style="padding: 15px;">
        <div class="col-xs-3">
            <img style="width: 170px;" src="<?= $siteinfo['logo'] ?>" />
        </div>
        <div class="col-xs-5" style="text-align: center;">
            <h2 class="help-text-print">Giao hàng thu tiền tại nhà trên toàn quốc</h2>
            <p>Liên hệ: <?= $siteinfo['phone'] ?></p>
        </div>
        <div class="col-xs-4">
            <p>
                <?= $siteinfo->company ?>
                <br/>
                <?= $siteinfo->address ?>
            </p>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6">
                    <p><b>Tên khách</b>: <?= $model->name ?></p>
                    <p><b>Điện thoại</b>: <?= $model->phone ?></p>
                    <p><b>Địa chỉ</b>: <?= $model->address ?></p>
                    <p><b>Mã đơn hàng</b>: #<?= $model->id ?></p>
                </div>
                <div class="col-xs-6">
                    <p>&nbsp;</p>
                    <p><b>Email</b>: <?= $model->email ?></p>
                </div>
            </div>
        </div>
        <table class="table table-bordered" id="table-order">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Giá tiền</th>
                    <th>SL</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                foreach ($products as $product) {
                    $total_item =  $product['price'] * $product['quantity'];
                    $total_price += $total_item;
                    ?>
                    <tr>
                        <td><?= $product['name'] ?></td>
                        <td><?= number_format($product['price'], 0, ',', '.') ?> đ</td>
                        <td><?= $product['quantity'] ?></td>
                        <td><?= number_format($total_item, 0, ',', '.') ?> đ</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <div class="clearfix"></div>
        <p class="pull-right text-result">
            <i class="fa fa-money" style="width: 15px;"></i> 
            <span>
                Tổng tiền đơn hàng: 
            </span>
            <b style="color: red"><?= number_format($total_price, 0, ',', '.') ?> đ</b>
        </p>

        <div class="clearfix"></div>
        <p class="pull-right text-result">
            <i class="fa fa-money" style="width: 15px;"></i> 
            <span>
                Phí ship: 
            </span>
            <b style="color: red"><?= ($model->shipfee > 0) ? number_format($model->shipfee, 0, ',', '.').' đ' : 'Liên hệ' ?></b>
        </p>
        <div class="clearfix"></div>
        <p class="pull-right text-result">
            <i class="fa fa-money" style="width: 15px;"></i> 
            <span>
                Tổng tiền thanh toán: 
            </span>
            <?php
            $price_real = $total_price - $model->money_customer_transfer + $model->shipfee;
            ?>
            <b style="color: red"><?= number_format($price_real, 0, ',', '.') ?> đ</b>
        </p>
        <div class="clearfix"></div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#btnPrint").click(function () {
            var contents = $("#wrap-print-order").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({"position": "absolute", "top": "-1000000px"});
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title><?= $siteinfo->company ?></title>');
            frameDoc.document.write('</head><body>');
            //Append the external CSS file.
            frameDoc.document.write('<link href="<?= \yii\helpers\Url::home() ?>gentelella/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />');
            frameDoc.document.write('<link href="<?= \yii\helpers\Url::home() ?>css/site.css" rel="stylesheet" type="text/css" />');
            frameDoc.document.write('<link href="<?= \yii\helpers\Url::home() ?>gentelella/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />');
            frameDoc.document.write('<link href="<?= \yii\helpers\Url::home() ?>gentelella/build/css/custom.min.css" rel="stylesheet" type="text/css" />');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
        });
    });
</script>

<script type="text/javascript">
    function printOrder() {
        var prtContent = document.getElementById('wrap-print-order');
        var WinPrint = window.open('', '', 'letf=0,top=0,width=1000,height=600');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    }
</script>
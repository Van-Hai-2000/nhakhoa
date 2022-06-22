<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\order\Order;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
?>
<button type="button" id="btnPrint" class="btn btn-lg btn-primary">In danh sách</button>
<div id="list-order">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name' => [
                'header' => 'name',
                'value' => function($model) {
                    return $model->name;
                }
            ],
//                            'email:email',
            'phone' =>  [
                'header' => 'phone',
                'value' => function($model) {
                    return $model->phone;
                }
            ],
            'address' =>  [
                'header' => 'address',
                'value' => function($model) {
                    return $model->address;
                }
            ],
            // 'district_id',
            // 'province_id',
            [
                'header' => 'order_total',
                'value' => function($model) {
                    return number_format($model->order_total, 0, ',', '.');
                },
                'footer' => common\models\order\search\OrderSearch::getTotal($dataProvider->models, 'order_total'),
            ],
        ],
    ]);
    ?>
</div>
<script type="text/javascript">
    $(function () {
        $("#btnPrint").click(function () {
            var contents = $("#list-order").html();

            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({"position": "absolute", "top": "-1000000px"});
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>Duy Hiển</title>');
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
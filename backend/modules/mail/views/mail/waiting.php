<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\shop\Shop;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="center">
    <br/>
        Đã gủi: <?= $end;  ?> / <?= $count ?>
    <hr>
    <?php if($end < $count) { ?>
        <button class="btn btn-danger cancer">Hủy gửi</button>
        <script type="text/javascript">
            var href = '<?= Url::to(['/mail/mail/send-mail', 'page' => $page]) ?>';
            $.ajax({
                url: href,
                type: 'POST',
                data: <?= json_encode($post) ?>,
                success: function(result){
                    $('.box-mail').html(result);
                }
            }); 
            $('.cancer').click(function () {
                location.reload();
            });
        </script>
    <?php } else { ?>
        <button class="btn btn-primary cancer">Đã gửi</button>
        <script type="text/javascript">
            $('.cancer').click(function () {
                location.reload();
            });
        </script>
    <?php } ?>
</div>

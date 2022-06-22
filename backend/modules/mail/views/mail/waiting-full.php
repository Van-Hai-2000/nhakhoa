<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\shop\Shop;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Đang gửi mail';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-inde">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-mail">
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
                </div>
            </div>
        </div>
    </div>
</div>
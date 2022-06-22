<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\shop\Shop;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gửi mail';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="<?php echo Yii::$app->homeUrl ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        CKEDITOR.replace("content", {
            height: 400,
            language: '<?php echo Yii::$app->language ?>'
        });

    });
</script>
<div class="user-inde">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-mail">
                    <form action="<?= Url::to(['/mail/mail/send-mail', 'page' => 0]) ?>" id="form-send" method="POST" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="sr-only" for="">Tiêu đề</label>
                            <select class="form-control" id="type" name="type">
                                <option value="-1">Kiểm thử</option>
                                <option value="0">Tất cả</option>
                                <option value="1">Tất cả người dùng thường</option>
                                <option value="2">Tất cả chủ gian hàng</option>
                            </select>
                         </div>
                        <div class="form-group">
                            <label class="sr-only" for="">Tiêu đề</label>
                            <input type="text" required="" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề(*)">
                        </div>
                         <div class="form-group">
                            <label class="sr-only" for="">Nhập nội dung</label>
                            <textarea id="content" required="" class="form-control" name="content"  placeholder="Nhập nội dung gửi(*)" rows="4"></textarea>
                         </div>
                         <div class="form-group">
                            <label class="sr-only" for="">Link đính đính kém(Nếu có)</label>
                            <input type="text" class="form-control" id="link" name="link" placeholder="Nhập link đính đính kém(Nếu có)">
                         </div>

                         <div class="form-group">
                             <div class="col-sm-10 col-sm-offset-0">
                                <p id="orre-form" style="color: red"></p>
                                <button name="sendMail" class="btn btn-primary updateajax">Gửi</button>
                             </div>
                         </div>
                    </form>             
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script type="text/javascript">
    jQuery('.updateajax').on('click', function() {
            $('#form-send').submit();
            if($('#title').val() != '') {
                $(this).css('display', 'none');
                var href = '<?= Url::to(['/mail/mail/send-mail', 'page' => 0]) ?>';
                $.ajax({
                    url: href,
                    type: 'POST',
                    data: $('#form-send').serialize(),
                    success: function(result){
                        $('.box-mail').html(result);
                    }
                });
                $('html, body').animate({scrollTop: 0}, 400);
            } else {
                $('#orre-form').html('Vui lòng nhập đủ thông tin trong ô (*)');
            }
            return false;
        });
    $('#form-send').submit(function() {
        return false;
    })
</script> -->

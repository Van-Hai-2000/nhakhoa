<?php
use yii\helpers\Url;
$this->title = 'Cấu hình số điện thoại nhận OTP';
?>
<style type="text/css">
    #otp-form input{
        padding: 5px 5px 5px 10px;
    }
    #save-phone {
        padding: 5px 20px;
        margin-left: 20px;
    }
    #otp-form {
        margin-top: 35px;
    }
</style>
<div class="row">
    <div class="news-category-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Cấu hình số điện thoại nhận OTP</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <p>Số điện thoại đang nhận: <b><?= $model->phone ?></b></p>
                        <form id="otp-form" action="" method="POST">
                            <input type="" id="phone-change" name="phone" placeholder="Nhập số điện thoại mới">
                            <button id="save-phone">Lưu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="box-responce" class="hidden"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#save-phone').click(function () {
            if(confirm('Xác thực OPT tới số "<?= $model->phone ?>" để lưu thay đổi. Nếu bị mất số điện thoại vui lòng liên hệ QTV để được thay đổi.')) {
                loadAjaxPost('<?= \yii\helpers\Url::to(['/gcacoin/otp/get-otp']) ?>', {}, $('#box-responce'));
            }
            return false;
        });
    });
</script>
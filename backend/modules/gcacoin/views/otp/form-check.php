<script type="text/javascript">
    var otp = prompt("<?= isset($error) ? $error.'. ' : '' ?>Nhập mã otp", "");
    if(otp!= null) {
        loadAjax('<?= \yii\helpers\Url::to(['/gcacoin/otp/check-otp']) ?>', {otp : otp}, $('#box-responce'));
    }
</script>
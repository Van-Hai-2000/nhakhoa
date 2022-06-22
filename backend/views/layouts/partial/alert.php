<style type="text/css">
    #alertBox .yes  , #alertBox .no {
        position: relative;
        margin: 5px auto;
        padding: 7px;
        border: 0 none;
        width: 70px;
        font: 0.7em verdana,arial;
        text-transform: uppercase;
        text-align: center;
        color: #FFF;
        background-color: #dbbf6d;
        border-radius: 3px;
        text-decoration: none;
        margin-left: 60px;
        font-size: 11px;
        margin-top: 10px;
    }
    #alertBox .no  {
        background: #EA4335 !important;
    }
    .indboxt a {
        cursor: pointer;
    }
    #alertBox .indboxt {
        padding-bottom: 25px;
    }
    #alertBox .indboxt input {
        height: 34px;
        width: 250px;
        padding: 0px 10px;
    }
</style>
<!-- alert -->
<script type="text/javascript">
    var ALERT_TITLE = "Thông báo";
    var ALERT_BUTTON_TEXT = "Xác nhận";
    var ALERT_CONFIRM_TEXT_OK = "Đồng ý";
    var ALERT_CONFIRM_TEXT_CAN = "Hủy bỏ";
    if(document.getElementById) {
        window.alert = function(txt) {
            createCustomAlert(txt);
        }
    }
    function createCustomAlert(txt) {
        $('body').css('overflow', 'hidden');
        d = document;
        if(d.getElementById("modalContainer")) return;
        $('body').append('<div id="modalContainer"><div id="alertBox" style="display: block;"><div class="indboxt"><h3>'+ALERT_TITLE+'</h3><p>'+txt+'</p><a id="closeBtn">'+ALERT_BUTTON_TEXT+'</a></div></div></div>');
    }
    function removeCustomAlert() {
        $('body').css('overflow', 'auto');
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
    }
    jQuery(document).on('click', '#closeBtn', function () {
        removeCustomAlert();
    });
</script>
<style type="text/css">
    #modalContainer, .modalContainer {
        width: 100%;
        height: 100vh !important;
        position: fixed;
        z-index: 9999999999;
        display: flex;
        top: 0px;
        left: 0px;
    }

    #alertBox, .alertBox {
        position:relative;
        margin:50px auto;
        width:300px;
        
    }
    #alertBox .indboxt, .alertBox .indboxt {
        width:100%;
        min-height: 128px;
        border:1px solid #0e8238;
        background-color:#fff;
        background-repeat:no-repeat;
        background-position:20px 30px;
    }

    #alertBox h3 {
        margin:0;
        font:bold 0.9em verdana,arial;
        background-color:#dbbf6d;
        color:#FFF;
        border-bottom:1px solid #0e8238;
        padding:8px 0 8px 5px;
    }

    #alertBox p {
        text-align: center;
        margin-top: 5px;
        font-size: 14px;
        padding: 7px 15px;
    }

    #alertBox #closeBtn {
        display:block;
        position:relative;
        margin:5px auto;
        padding:7px;
        border:0 none;
        width:70px;
        font:0.7em verdana,arial;
        text-transform:uppercase;
        text-align:center;
        color:#FFF;
        background-color:#dbbf6d;
        border-radius: 3px;
        text-decoration:none;
    }

    /* unrelated styles */

    #mContainer {
        position:relative;
        width:600px;
        margin:auto;
        padding:5px;
        border-top:2px solid #0e8238;
        border-bottom:2px solid #0e8238;
        font:0.7em verdana,arial;
    }


    #credits {
        position:relative;
        margin:25px auto 0px auto;
        width:350px; 
        font:0.7em verdana;
        border-top:1px solid #0e8238;
        border-bottom:1px solid #0e8238;
        height:90px;
        padding-top:4px;
    }

    #credits img {
        float:left;
        margin:5px 10px 5px 0px;
        border:1px solid #0e8238;
        width:80px;
        height:79px;
    }

    .important {
        background-color:#F5FCC8;
        padding:2px;
    }

    code span {
        color:green;
    }
    .fb_comment iframe {
        width: 100% !important;
    }
</style>
<div id="SaveDataSystem" style="display: none;"></div>
<!-- confirm -->
<script type="text/javascript">
    function noConFirm() {}
    function yesConFirm() {}
    window.confirmCS = function(txt, data) {
        $('body').css('overflow', 'hidden');
        $('#SaveDataSystem').html(JSON.stringify(data));
        d = document;
        if(d.getElementById("modalContainer")) return;
        $('body').append('<div id="modalContainer"><div id="alertBox" style="display: inline-block;"><div class="indboxt"><h3>'+ALERT_TITLE+'</h3><p>'+txt+'</p><a id="confirmYes" class="yes">'+ALERT_CONFIRM_TEXT_OK+'</a><a id="confirmNo" class="no">'+ALERT_CONFIRM_TEXT_CAN+'</a></div></div></div>');
    };
    jQuery(document).on('click', '#confirmYes', function () {
        removeCustomAlert();
        data = $('#SaveDataSystem').html();
        data = data ? JSON.parse(data) : null;
        yesConFirm(data);
    });
    jQuery(document).on('click', '#confirmNo', function () {
        removeCustomAlert();
        data = $('#SaveDataSystem').html();
        data = data ? JSON.parse(data) : null;
        noConFirm(data);
    });
</script>

<script type="text/javascript">
    function noPrompt() {}
    function yesPrompt() {}
    window.promptCS = function(txt,placeholder, data) {
        $('body').css('overflow', 'hidden');
        $('#SaveDataSystem').html(JSON.stringify(data));
        d = document;
        if(d.getElementById("modalContainer")) return;
        $('body').append('<div id="modalContainer"><div id="alertBox" style="display: inline-block;"><div class="indboxt"><h3>'+ALERT_TITLE+'</h3><p>'+txt+'</p><p><input placeholder="'+placeholder+'" id="PromptCSInput"/></p><a id="promptYes" class="yes" >'+ALERT_CONFIRM_TEXT_OK+'</a><a id="promptNo" class="no" >'+ALERT_CONFIRM_TEXT_CAN+'</a></div></div></div>');
    };
    jQuery(document).on('click', '#promptYes', function () {
        value = $('#PromptCSInput').val();
        removeCustomAlert();
        data = $('#SaveDataSystem').html();
        data = data ? JSON.parse(data) : null;
        yesPrompt(value, data);
    });
    jQuery(document).on('click', '#promptNo', function () {
        value = $('#PromptCSInput').val();
        removeCustomAlert();
        data = $('#SaveDataSystem').html();
        data = data ? JSON.parse(data) : null;
        noPrompt(value, data);
    });
</script>
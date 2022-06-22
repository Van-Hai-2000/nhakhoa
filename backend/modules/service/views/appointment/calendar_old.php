<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/15/2021
 * Time: 10:24 AM
 */

use yii\helpers\Html;

$this->title = 'Danh sách lịch hẹn';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- bắt buộc CSS -->
<link rel="stylesheet" href="<?= Yii::$app->homeUrl ?>css/calendar/fullcalendar.min.css"/>


<!-- Có thể có hoặc k -->
<!--<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>-->

<style>
    .daden .fc-content{
        border: 1px solid green;
        background-color: green;
    }
</style>
<div class="branch-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo mới', ['create','id' => ''], ['class' => 'btn btn-success pull-right']) ?>
                    <button class="btn btn-primary pull-right type_view">Xem dạng danh sách</button>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="main-container ace-save-state" id="main-container">
                        <div class="main-content">
                            <div class="main-content-inner">
                                <div class="page-content">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <!-- PAGE CONTENT BEGINS -->
                                            <div class="row">
                                                <div class="col-sm-9">
                                                    <div class="space"></div>

                                                    <div id="calendar"></div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="widget-box transparent">
                                                        <div class="widget-header">
                                                            <h4>Chi tiết lịch hẹn</h4>
                                                        </div>

                                                        <div class="widget-body">
                                                            <div class="widget-main no-padding appointment_detail">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PAGE CONTENT ENDS -->
                                        </div><!-- /.col -->
                                    </div><!-- /.row -->
                                </div><!-- /.page-content -->
                            </div>
                        </div><!-- /.main-content -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- bắt buộc JS -->
<script type="text/javascript" src="<?= Yii::$app->homeUrl ?>js/calendar/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->homeUrl ?>js/calendar/moment.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->homeUrl ?>js/calendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->homeUrl ?>js/calendar/bootbox.js"></script>

<script type="text/javascript">


    $('.type_view').click(function () {
        $.ajax({
            type: 'GET',
            cache: false,
            url: '<?= \yii\helpers\Url::to(['set-view']) ?>',
            data: {
                type: 'list'
            },
            success: function (data) {
                window.location.reload();
            }
        });
    });

    var days = JSON.parse('<?= json_encode($model) ?>');
    jQuery(function ($) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();


        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev, next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: days,
            timeFormat: 'H:mm',
            editable: false,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function (date) { // this function is called when something is dropped
                console.log('ok')

            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                var dt = Date.parse(start);
                console.log(dt);
            },
            eventClick: function (calEvent, jsEvent, view) {
                var html = '';
                html += addItem('Họ và tên',calEvent.cs_name);
                html += addItem('Thời gian',calEvent.cs_time);
                html += addItem('Chi nhánh',calEvent.cs_branch);
                html += addItem('Số điện thoại',calEvent.cs_phone);
                html += addItem('Bác sỹ khám',calEvent.cs_doctor);
                html += addItem('Nhóm thủ thuật',calEvent.cs_category);
                html += addItem('Ghi chú',calEvent.cs_note);
                html += addItem('Trạng thái',calEvent.cs_status);

                $('.appointment_detail').empty().append(html);

            },
        });

        $(document).on("click", ".fc-prev-button", function () {

        });

        $(document).on("click", ".fc-next-button", function () {

        });

    });


    function addItem(label,value) {
        var html = '<div class="external-event" data-class="label-grey">\n' +
            '                                                                    <i class="ace-icon fa fa-arrows"></i>\n' +
            '                                                                    <label for="">'+label+': </label><span class="cs_name"> '+value+' </span>\n' +
            '                                                                </div>';
        return html;
    }

    function abc(t) {
        console.log('hihi');
    }
</script>
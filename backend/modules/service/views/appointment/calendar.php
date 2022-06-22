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
    .daden .fc-content {
        border: 1px solid green;
        background-color: green;
    }

    .fc-left {
        display: flex;
    }

    .select_month {
        width: 170px;
    }
</style>
<div class="branch-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo mới', ['create', 'id' => ''], ['class' => 'btn btn-success pull-right']) ?>
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
    var list_year = JSON.parse('<?= json_encode($list_year) ?>');
    var current_year = '<?= $current_year ?>';
    var html_year = '<select class="select_year form-control">';
    $.each(list_year, function (key, value) {
        if (current_year == value) {
            html_year += '<option value="' + value + '" selected>' + value + '</option>';
        } else {
            html_year += '<option value="' + value + '">' + value + '</option>';
        }

    });
    html_year += '</select>';
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            monthNames: ['Tháng 1 - ', 'Tháng 2 - ', 'Tháng 3 - ', 'Tháng 4 - ', 'Tháng 5 - ', 'Tháng 6 - ', 'Tháng 7 - ', 'Tháng 8 - ', 'Tháng 9 - ', 'Tháng 10 - ', 'Tháng 11 - ', 'Tháng 12 - '],
            dayNamesShort: ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'],
            header: {
                left: '',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: moment().format("YYYY-MM-DD"),
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: days,
            eventClick: function (calEvent, jsEvent, view) {
                var html = '';
                html += addItem('Họ và tên', calEvent.cs_name);
                html += addItem('Thời gian', calEvent.cs_time);
                html += addItem('Chi nhánh', calEvent.cs_branch);
                html += addItem('Số điện thoại', calEvent.cs_phone);
                html += addItem('Bác sỹ khám', calEvent.cs_doctor);
                html += addItem('Nhóm thủ thuật', calEvent.cs_category);
                html += addItem('Ghi chú', calEvent.cs_note);
                html += addItem('Trạng thái', calEvent.cs_status);
                html += addItemLink('Mã HSBA', calEvent.cs_medical, calEvent.cs_medical);

                $('.appointment_detail').empty().append(html);

            },
        });

        $(".fc-left").append('<select class="select_month form-control"><option value="1">Tháng 1</option><option value="2">Tháng 2</option><option value="3">Tháng 3</option><option value="4">Tháng 4</option><option value="5">Tháng 5</option><option value="6">Tháng 6</option><option value="7">Tháng 7</option><option value="8">Tháng 8</option><option value="9">Tháng 9</option><option value="10">Tháng 10</option><option value="11">Tháng 11</option><option value="12">Tháng 12</option></select>');
        $(".fc-left").append(html_year);

        $(".select_month").on("change", function (event) {
            var current_month = this.value;
            var current_year = $(".select_year").val();

            getValueByDate(current_month, current_year);
        });
        $(".select_year").on("change", function (event) {
            var current_month = $(".select_month").val();
            var current_year = this.value;
            getValueByDate(current_month, current_year);
        });

        $('.fc-month-button').text('Tháng');
        $('.fc-agendaWeek-button').text('Tuần');
        $('.fc-agendaDay-button').text('Ngày');

        $(".fc-more").each(function () {
            var text = $(this).text();
            text = text.replace("more", "người khác");
            $(this).text(text);
        });

    });

    function addItem(label, value) {
        var html = '<div class="external-event" data-class="label-grey">\n' +
            '                                                                    <i class="ace-icon fa fa-arrows"></i>\n' +
            '                                                                    <label for="">' + label + ': </label><span class="cs_name"> ' + value + ' </span>\n' +
            '                                                                </div>';
        return html;
    }

    function addItemLink(label, value, id) {
        var link = '<?= \yii\helpers\Url::to(['/user/medical-record/add']) ?>?id=' + id;
        var html = '<div class="external-event" data-class="label-grey">\n' +
            '                                                                    <i class="ace-icon fa fa-arrows"></i>\n' +
            '                                                                    <label for="">' + label + ': </label><a href="' + link + '"><span class="cs_name"> ' + value + ' </span></a>\n' +
            '                                                                </div>';
        return html;
    }

    function getValueByDate(month, year) {
        $.ajax({
            type: 'GET',
            cache: false,
            url: '<?= \yii\helpers\Url::to(['get-value']) ?>',
            data: {
                month: month,
                year: year
            },
            success: function (response) {
                var dt = JSON.parse(response);
                $('#calendar').fullCalendar('changeView', 'month', month);
                $('#calendar').fullCalendar('gotoDate', year + "-" + month);
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', dt);
                $('#calendar').fullCalendar('rerenderEvents');
            }
        });
    }
</script>
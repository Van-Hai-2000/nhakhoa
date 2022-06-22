<?php 
if($last) { 
    $time = strtotime(date('d-m-Y', time())) + 24*60*60 -1;
} else {
    $time = strtotime(date('d-m-Y', time()));
}
$format_php = 'd-m-Y H:i';

switch ($format) {
    case 'DD-MM-YYYY':
            $format_php = 'd-m-Y';
        break;
    case 'HH:mm':
            $format_php = 'H:i';
        break;
}
?>
<script>
    $(document).ready(function () {
        $('#input-date-<?= $model->attribute ?>').daterangepicker({
            timePicker: true,
            timePickerIncrement: 1,
            timePicker24Hour: true,
            autoUpdateInput: false,
            showDropdowns: true,
            locale: {
                format: '<?=  $format ?>',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            },
            singleDatePicker: true,
            calender_style: 'picker_4'
        }, function(chosen_date) {
            $('#input-date-<?= $model->attribute ?>').val(chosen_date.format('<?=  $format?>'));
        });
        $('#input-date-<?= $model->attribute ?>').click(function() {
            if(!$(this).val()) {
                <?php if($last) { ?>
                    setTimeout(function(){
                        $('.hourselect>option:last-child').attr('selected', 'selected');
                        $('.minuteselect>option:last-child').attr('selected', 'selected');
                    }, 150);
                <?php } ?>
                $(this).val('<?= date($format_php, $time) ?>');
            }
        });
        
    });
</script>
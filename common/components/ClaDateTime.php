<?php

namespace common\components;

/**
 * date time proccess
 *
 * @author MinhBN
 */
class ClaDateTime {

    const SECONDS_OF_DAY = 86400;

    /**
     * return days of week array
     * @return type
     */
    static function getDaysOfWeek($options = array()) {
        $short = isset($options['short']) ? $options['short'] : false;
        if (!$short) {
            return array(
                0 => Yii::t('datetime', 'sunday'),
                1 => Yii::t('datetime', 'monday'),
                2 => Yii::t('datetime', 'tuesday'),
                3 => Yii::t('datetime', 'wednesday'),
                4 => Yii::t('datetime', 'thursday'),
                5 => Yii::t('datetime', 'friday'),
                6 => Yii::t('datetime', 'saturday'),
            );
        } else {
            return array(
                0 => Yii::t('datetime', 'sunday_short'),
                1 => Yii::t('datetime', 'monday_short'),
                2 => Yii::t('datetime', 'tuesday_short'),
                3 => Yii::t('datetime', 'wednesday_short'),
                4 => Yii::t('datetime', 'thursday_short'),
                5 => Yii::t('datetime', 'friday_short'),
                6 => Yii::t('datetime', 'saturday_short'),
            );
        }
    }

    /**
     * get day in week from date
     * @param type $date
     * @return type
     */
    static function getDaysOfWeekFromDate($date = '') {
        $result = array();
        if ($date) {
            $dayKey = date("N", strtotime($date));
            $daysOfWeekArr = self::getDaysOfWeek();
            if (isset($daysOfWeekArr[$dayKey])) {
                $result[$dayKey] = $daysOfWeekArr[$dayKey];
            }
        }
        return $result;
    }

    /**
     * Kiểm tra xem khoảng time có giao nhau không
     */
    static function checkIntersectTime($startTime = 0, $endTime = 0, $breakTimes = array()) {
        foreach ($breakTimes as $breakTime) {
            $overlap = max(0, min($endTime, $breakTime['end_time']) - max($startTime, $breakTime['start_time']));
            if ($overlap > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * get day text from index
     */
    static function getDayTextFromIndex($index = 0, $options = array()) {
        $index = (int) $index;
        $daysOfWeekArr = self::getDaysOfWeek($options);
        if (isset($daysOfWeekArr[$index])) {
            return $daysOfWeekArr[$index];
        }
        return '';
    }

    /**
     * lấy số ngày của một tháng trong năm
     * @param int $month
     * @param type $year
     * @return type
     */
    static function get_days_in_month($month = 0, $year = 0) {
        if (!$month) {
            $month = 1;
        }
        if (!$year) {
            $year = (int) date('Y');
        }
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    /**
     * return months in year
     * @return type
     */
    static function getMonths() {
        return array(
            1 => Yii::t('datetime', 'january'),
            2 => Yii::t('datetime', 'february'),
            3 => Yii::t('datetime', 'march'),
            4 => Yii::t('datetime', 'april'),
            5 => Yii::t('datetime', 'may'),
            6 => Yii::t('datetime', 'june'),
            7 => Yii::t('datetime', 'july'),
            8 => Yii::t('datetime', 'august'),
            9 => Yii::t('datetime', 'september'),
            10 => Yii::t('datetime', 'october'),
            11 => Yii::t('datetime', 'november'),
            12 => Yii::t('datetime', 'december'),
        );
    }

    /**
     * return month text
     * @param type $month
     * @return string
     */
    static function getMonthText($month = 0) {
        $month = (int) $month;
        $months = self::getMonths();
        if (isset($months[$month])) {
            return $months[$month];
        }
        return '';
    }

    /**
     * 
     */
    static function getTimeTextFromTimestamp($timestamp = 0, $format = 'H:i A') {
        $timestamp = (int) $timestamp;
        if ($timestamp) {
            return gmdate($format, $timestamp);
        } else {
            return Yii::t('datetime', 'closed');
        }
    }

    static function getTimeZones($options = array()) {
        $return = array();
        if (isset($options['none'])) {
            $return = array('' => Yii::t('app', 'none'));
        }
        $timezones = array(
            'Pacific/Honolulu' => '(UTC -10) Hawaii-Aleutian Standard',
            'America/Adak' => "(UTC -9) Alaska Standard",
            'America/Anchorage' => "(UTC -8) Pacific Standard",
            'America/Los_Angeles' => "(UTC -7) Mountain Standard",
            'America/Denver' => "(UTC -6) Central Standard",
            'America/Chicago' => "(UTC -5) Eastern Standard",
            'America/New_York' => "(UTC -4) Atlantic Standard ",
        );
        $return = $return + $timezones;
        return $return;
    }

    /**
     * trừ 2 ngày 
     * return int so ngay
     */
    static function subtractDate($date1 = '', $date2 = '') {
        $dateTimeStamp1 = strtotime($date1);
        $dateTimeStamp2 = strtotime($date2);
        $diff = $dateTimeStamp2 - $dateTimeStamp1;
        $return = $diff / 86400;
        return $return;
    }

    /**
     * Creating date collection between two dates
     *
     * <code>
     * <?php
     * # Example 1
     * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
     *
     * # Example 2. you can use even time
     * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
     * </code>
     *
     * @author Ali OYGUR <alioygur@gmail.com>
     * @param string since any date, time or datetime format
     * @param string until any date, time or datetime format
     * @param string step
     * @param string date of output format
     * @return array
     */
    static function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y') {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while ($current <= $last) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

}

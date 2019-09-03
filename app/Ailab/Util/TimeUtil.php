<?php declare(strict_types=1);


namespace App\Ailab\Util;

use phpDocumentor\Reflection\Types\Integer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use UnexpectedValueException;

/**
 * Class TimeUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="timeUtil")
 */
class TimeUtil
{
	const TTL_SEVEN_DAY = 7;
    const TTL_ONE_MINUTE = 60;
    const TTL_HALF_HOUR = 1800;
    const TTL_ONE_HOUR = 3600;
    const TTL_ONE_DAY = 86400;
    const TTL_ONE_WEEK = 604800; // 86400 * 7;
    const TTL_TWO_WEEKS = 1209600;
    const TTL_TWO_DAYS = 172800;// 86400 * 2;
    const TTL_FIVE_DAYS = 432000;// 86400  * 5;
    const TTL_ONE_MONTH = 2592000;//86400 * 30;

    const ORIGIN_DATETIME_STR = '0000-00-00 00:00:00';

    private static $m_time;

    public static function getServerTime() {
        if ( !self::$m_time ) {
            self::$m_time = time();
        }
        return self::$m_time;
    }

    public static function getServerMicroTime() {
        return microtime( 1 );
    }

    public static function getServerShortDateTime($time) {
        $time || $time = self::getServerTime();
        return date( 'Ymd', $time);
    }

    public static function getServerShortDate() {
        return date( 'Ymd', self::getServerTime() );
    }

    public static function getServerDate() {
        return date( 'Y-m-d', self::getServerTime() );
    }

    public static function getServerDateTime() {
        return date( 'Y-m-d H:i:s', self::getServerTime() );
    }
    
    public static function getServerShortMinuteTime() {
        return date( 'YmdHi', self::getServerTime() );
    }
    public static function getServerMonthDate($time = null) {
        $time || $time = self::getServerTime();
        return date( 'Y-m', $time );
    }
    
    public static function getServerStartDateTime($time = null){
        $time || $time = self::getServerTime();
        return date( 'Y-m-d 00:00:00', $time );
    }
    public static function getServerEndDateTime($time = null){
        $time || $time = self::getServerTime();
        return date( 'Y-m-d 23:59:59', $time );
    }

    public static function getTodayTime( $time = null ) {
        $time || $time = self::getServerTime();
        return strtotime( date( 'Y-m-d 00:00:00', $time ) );
    }

    public static function getLastMonthStartTime( $time = null ) {
        return strtotime( date( 'Y-m-1 00:00:00', self::getLastMonthEndTime( $time ) ) );
    }
    public static function getLastMonthStartDate($time = null){
        return date( 'Y-m-1 00:00:00', self::getLastMonthEndTime( $time ) );
    }
    public static function getLastMonthEndDate( $time = null ) {
        return date( 'Y-m-d 00:00:00', TimeUtil::getLastMonthEndTime( $time ) );
    }

    public static function getLastMonthEndTime( $time = null ) {
        return self::getThisMonthStartTime( $time ) - 1;
    }

    public static function getThisMonthStartTime( $time = null ) {
        $time || $time = self::getServerTime();
        return strtotime( date( 'Y-m-1 00:00:00', $time ) );
    }

    public static function getThisMonthEndTime( $time = null ) {
        $nextStartTime = self::getThisMonthStartTime($time);
        return strtotime( '+1 month', $nextStartTime ) - 1;
    }

    public static function getTomorrowTime( $time = null ) {
        return self::getTodayTime( $time ) + self::TTL_ONE_DAY;
    }

    public static function getYesterdayTime( $time = null ) {
        return self::getTodayTime( $time ) - self::TTL_ONE_DAY;
    }

    public static function getFrontdayTime( $time = null, $lateday ) {
        return self::getTodayTime( $time ) - $lateday * self::TTL_ONE_DAY;
    }

    public static function getHistoryDayDate($historyDay = 0) {
        return date("Y-m-d H:i:s", strtotime(" -".$historyDay." days"));
    }

    public static function getFutureDayDate($historyDay = 0) {
        return date("Y-m-d H:i:s", strtotime(" +".$historyDay." days"));
    }

    public static function formatTime( $time ) {
        if (!$time) {
            return '';
        }
        return date( 'Y-m-d H:i:s', $time );
    }

    public static function formatDate( $time ) {
        if (!$time) {
            return '';
        }
        return date( 'Y-m-d', $time );
    }

    public static function formatShortDate( $time ) {
        if (!$time) {
            return '';
        }
        return date( 'Y/m/d', $time );
    }

    public static function dateToTime( $date ) {
        if ( !$date ) {
            return 0;
        }
        if ( self::ORIGIN_DATETIME_STR == $date ) {
            return 0;
        }
        return strtotime( $date );
    }

    public static function getPastMonthTime($month) {
        if (!$month) {
            return 0;
        }

        return strtotime("-{$month} month", self::getServerTime());
    }

    public static function getNextYearTime( $time = null ) {
        $time || $time = self::getServerTime();

        return strtotime("next year", $time);
    }

    /**
     * 将秒数 转成 HH:MM:SS 形式的字符串
     *
     * @param int $second
     * @return string
     */
    public static function secondsToTime( $second ) {
        $h = floor( $second / self::TTL_ONE_HOUR );
        $m = floor( ( $second % self::TTL_ONE_HOUR ) / self::TTL_ONE_MINUTE );
        $s = floor( $second % self::TTL_ONE_MINUTE );
        $timeStr = '';
        if ( $h > 0 ) {
            $timeStr .= str_pad( $h, 2, '0', STR_PAD_LEFT ) . ':';
        }
        $timeStr .= str_pad( $m, 2, '0', STR_PAD_LEFT ) . ':';
        $timeStr .= str_pad( $s, 2, '0', STR_PAD_LEFT );
        return $timeStr;
    }

    public static function getBetweenDay($startDate = '', $endDate = '')
    {
        if (!($startDate && $endDate)) {
            return false;
        }
        $startTime = self::dateToTime($startDate);
        $endTime = self::dateToTime($endDate);
        if ($endTime < $startTime) {
            return false;
        }
        $betweenTime = $endTime - $startTime;
        $betweenDay = ceil($betweenTime / TimeUtil::TTL_ONE_DAY);
        return $betweenDay;
    }
    /**
     * make sure if time is in close range [starttime,endtime]
     *
     * @param int $time
     * @param int $starttime
     * @param int $endtime
     * @return boolean
     */
    public static function isBetween( $time, $starttime, $endtime ) {
        return $time >= $starttime && $time <= $endtime;
    }

    public static function isTodayTime( $time = 0 ) {
        if ( !$time ) {
            return false;
        }
        return self::isBetween( $time, self::getTodayTime(), self::getTomorrowTime() );
    }

    public static function isLastDayTime( $time = 0 ) {
        if ( !$time ) {
            return false;
        }
        return self::isBetween( $time, self::getYesterdayTime(), self::getTodayTime() );
    }

    /**
     * 获得下一个小时的时间
     *
     * @return bool|string
     */

    public static function getServerNextHourTime() {
        return date( 'Y-m-d H:i:00', (self::getServerTime() + self::TTL_ONE_HOUR));
    }

    /**
     * 获得上一小时的时间
     *
     * @return bool|string
     */
    public static function getServerLastHourTime() {
        return date( 'Y-m-d H:i:00', (self::getServerTime() - self::TTL_ONE_HOUR));
    }


    /**
     * 获得当前某一时间
     *
     * @param int $day_time
     * @param int $hour_time
     * @param int $minute_time
     * @return bool|string
     */
    public static function getServerLstCertainTime($day_time = 0, $hour_time = 0, $minute_time = 0){
        $diff_time = 0;
        if($day_time > 0){
            $diff_time += $day_time * self::TTL_ONE_DAY;
        }
        if($hour_time > 0){
            $diff_time += $hour_time * self::TTL_ONE_HOUR;
        }
        if($minute_time > 0){
            $diff_time += $minute_time * self::TTL_ONE_MINUTE;
        }
        return date( 'Y-m-d H:i:s', (self::getServerTime() - $diff_time));
    }

    /**
     * 把秒处理成 1m1s 的形式
     * @param int $second
     * @return string
     */
    public static function getMinutesAndSeconds(int $second) :string{
        if(!$second){
            return '0';
        }
        $min = floor($second / 60);
        $sec = $second % 60;
        if($min === 0){
            $ret = $sec . 's';
        }else if($sec === 0){
            $ret = $min . 'min';
        }else{
            $ret = $min . 'min' . $sec . 's';
        }
        return $ret;
    }

    /**
     * 把秒处理成 1m1s 的形式
     * @param int $second
     * @return string
     */
    public static function getMinutes(int $second) :string{
        if(!$second){
            return '0';
        }
        $min = floor($second / 60);
        $sec = $second % 60;
        if($min === 0){
            $ret = '0';
        }else{
            $ret = $min . 'min';
        }
        return $ret;
    }

}
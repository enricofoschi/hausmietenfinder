<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 14.01.15
 * Time: 15:06
 */

namespace DevDes\Helpers\Core;



class TimeHelper {

    /**
     * @param $date date input for the 'strtotime' function
     * @return string ticks
     */
    public static function GetTicks($string_date) {
        return strtotime($string_date);
    }
}
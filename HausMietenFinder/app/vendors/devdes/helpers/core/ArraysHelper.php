<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 09.01.15
 * Time: 23:50
 */

namespace DevDes\Helpers\Core;


class ArraysHelper {

    /**
     * @param $list list to iterate through
     * @param $matcher callback function that returns true if the element (passed as first parameter) is a match
     * @return mixed matched value or null
     */
    public static function FindFirst($list, $matcher) {
        foreach($list as $element) {
            if($matcher($element)) {
                return $element;
            }
        }
        return null;
    }
}
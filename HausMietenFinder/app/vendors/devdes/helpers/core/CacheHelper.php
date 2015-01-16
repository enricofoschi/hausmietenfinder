<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 11.01.15
 * Time: 22:02
 */

namespace DevDes\Helpers\Core;


class CacheHelper {

    /**
     * @param $key string unique key identifier
     * @param $getter callable getter to load the data
     * @return mixed requested data
     */
    public static function GetStatic($key, $getter)
    {
        if(!isset($_SESSION[$key])) {
            $_SESSION[$key] = $getter();
        }

        return $_SESSION[$key];
    }
}
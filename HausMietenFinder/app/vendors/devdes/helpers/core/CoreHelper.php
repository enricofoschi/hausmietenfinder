<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 09.01.15
 * Time: 21:16
 */

namespace DevDes\Helpers\Core;

class CoreHelper {

    /**
     * @return string project app folder
     */
    public static function GetAppRoot() {
        return __DIR__ . "/../../../../";
    }

    /**
     * @return string project root folder
     */
    public static function GetProjectRoot()
    {
        return __DIR__ . "/../../../../../";
    }

    /**
     * @return string base project relative url
     */
    public static function GetBaseUrl() {
        return ConfigHelper::GetMainConfig()->application->baseUri;
    }

    /**
     * @return string base project absolute url
     */
    public static function GetAbsoluteUrl() {
        return str_replace('//', '/', ConfigHelper::GetMainConfig()->application->absoluteUri . self::GetBaseUrl());
    }

}
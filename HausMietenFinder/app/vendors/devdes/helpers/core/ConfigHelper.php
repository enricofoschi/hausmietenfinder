<?php

namespace DevDes\Helpers\Core;

class ConfigHelper {

    /**
     * @return mixed main project configuration
     */
    public static function GetMainConfig() {

        return CacheHelper::GetStatic("main_config", function() {
            $config = include CoreHelper::GetAppRoot() . "config/config.php";
            $pwd_file = CoreHelper::GetAppRoot() . "/config/local.config.php";
            if(file_exists($pwd_file)) {
                include $pwd_file;
            } else {
                include CoreHelper::GetAppRoot() . "/config/local.config.default.php";
            }

            return $config;
        });
    }

    /**
     * @param $path string path to the config file
     * @return mixed configuration content
     */
    public static function GetConfig($path) {

        return CacheHelper::GetStatic("config_" . $path, function() use($path) {
            $content = file_get_contents($path);

            if(StringsHelper::EndsWith($path, ".json")) {
                $content = json_decode($content);
            }

            return $content;
        });
    }

}
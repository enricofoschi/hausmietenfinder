<?php

namespace DevDes\Helpers\Core;

class ConfigHelper {

    /**
     * @return mixed main project configuration
     */
    public static function GetMainConfig() {

        return CacheHelper::GetStatic("main_config", function() {
            return include CoreHelper::GetAppRoot() . "config/config.php";
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
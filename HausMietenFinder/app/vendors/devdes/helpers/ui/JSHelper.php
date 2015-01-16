<?php

namespace DevDes\Helpers\UI;

use DevDes\Helpers\Core\CoreHelper;
use DevDes\Helpers\Net\RequestHelper;

class JSHelper {

    /**
     * @return string JS Web Url
     */
    public static function GetUrl() {
        return CoreHelper::GetBaseUrl() . '-assets/getjs/';
    }

    /**
     * @param $identifier string concatenation identifier
     * @return string concatenated file URL
     */
    public static function GetConcatenatedTags($identifier) {
        if(RequestHelper::IsInDebugMode()) {
            return AssetsHelper::GetAllUrls(self::GetFolder(), $identifier, self::GetUrl(), self::GetScriptTags('%s'));
        } else {
            return self::GetScriptTags(CoreHelper::GetBaseUrl() . '-assets/getconcatenatedjs/' . $identifier);
        }
    }

    /**
     * @return string JS root folder
     */
    public static function GetFolder() {
        return CoreHelper::GetProjectRoot() . 'public/js/';
    }

    /**
     * @param $identifier string concatenation identifier defined in js/config.json
     * @return string concatenated JS content
     */
    public static function GetConcatenatedContent($identifier) {
        $content = AssetsHelper::GetConcatenatedContent(
            $identifier,
            self::GetFolder(),
            ";",
            $parsers = self::GetParsers()
        );

        return $content;
    }

    /**
     * @return array parsers to be used to format particular extensions
     */
    public static function GetParsers() {
        return array(
            "jsx" => function($content) {
                return self::ParseJSX($content);
            }
        );
    }

    /**
     * @param $file string file path
     * @return string file content
     */
    public static function GetFileContent($file) {
        return AssetsHelper::GetAssetContent(self::GetFolder(), $file, self::GetParsers());
    }

    /**
     * @param $content string content to parse
     * @return string parsed JSX > JS
     */
    public static function ParseJSX($content) {
        $temp_file = self::GetFolder() . "tmp_jsx_parsed_" . rand() . ".js";
        file_put_contents($temp_file, $content);

        $output = array();
        $return = 0;

        exec("/usr/local/bin/jsx " . $temp_file, $output, $return);
        unlink($temp_file);

        return implode("\n", $output);
    }

    /**
     * @param $file string file url
     * @return string <script> tags including $file
     */
    public static function GetScriptTags($file) {
        return '<script type="text/javascript" src="'. $file . '"></script>';
    }
}
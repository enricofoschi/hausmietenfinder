<?php

namespace DevDes\Helpers\UI;

use DevDes\Helpers\Core\CoreHelper;
use DevDes\Helpers\Core\CacheHelper;
use DevDes\Helpers\Net\RequestHelper;

class CSSHelper {

    /**
     * @return string lazy initialized mixins content
     */
    public static function GetMixins() {

        return CacheHelper::GetStatic("mixins", function() {
            return file_get_contents(self::GetFolder() . 'core/mixins.less');
        });
    }

    /**
     * @return string CSS Web Url
     */
    public static function GetUrl() {
        return CoreHelper::GetBaseUrl() . '-assets/getcss/';
    }

    /**
     * @param $identifier string concatenated file identifier
     * @return string concatenated file url
     */
    public static function GetConcatenatedTags($identifier) {
        if(RequestHelper::IsInDebugMode()) {
            return AssetsHelper::GetAllUrls(self::GetFolder(), $identifier, self::GetUrl(), self::GetLinkTags('%s'));
        } else {
            return self::GetLinkTags(CoreHelper::GetBaseUrl() . '-assets/getconcatenatedcss/' . $identifier);
        }
    }

    /**
     * @return string CSS root folder
     */
    public static function GetFolder() {
        return CoreHelper::GetProjectRoot() . 'public/css/';
    }

    /**
     * @param $file string file path relative to local public/css folder
     * @return string file content
     */
    public static function GetFileContent($file) {
        return AssetsHelper::GetAssetContent(self::GetFolder(), $file, self::GetParsers(), self::GetMixins());
    }

    /**
     * @param $identifier string identifier specified in css/config.json
     * @return string returns the LESS parsed CSS content for the specified identifier
     */
    public static function GetConcatenatedContent($identifier) {
        $content = AssetsHelper::GetConcatenatedContent(
            $identifier,
            self::GetFolder(),
            ".l{}", // requested to work around a lessphp bug (not reading properly the first class declaration)
            $parsers = self::GetParsers(),
            $pre_include = self::GetMixins()
        );

        //Use the built-in Jsmin filter
        $minifier = new \Phalcon\Assets\Filters\Cssmin();
        return $minifier->filter($content);

        return $content;
    }

    /**
     * @return array parsers to be used to format particular extensions
     */
    public static function GetParsers() {
        return array(
            "less" => function($content) {
                return self::ParseLess($content);
            }
        );
    }

    /**
     * @param $content string content to parse with less
     * @return string LESS parsed content
     */
    public static function ParseLess($content) {
        $less = new \lessc;
        return $less->compile($content);
    }

    /**
     * @param $file string file url
     * @return string <link> tags including $file
     */
    public static function GetLinkTags($file) {
        return '<link rel="stylesheet" type="text/css" href="'. $file . '">';
    }
}
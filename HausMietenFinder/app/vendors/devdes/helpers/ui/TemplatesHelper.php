<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 09.01.15
 * Time: 18:42
 */

namespace DevDes\Helpers\UI;

use DevDes\Helpers\Core\CoreHelper;
use DevDes\Helpers\Core\CacheHelper;

class TemplateFormatter {

    /**
     * @return array default placeholders for templates
     */
    public static function GetDefaultPlaceholders() {

        return CacheHelper::GetStatic("template_placeholders", function() {
            return array(
                "images_url" => CoreHelper::GetBaseUrl() . 'img/',
                "relative_url" => CoreHelper::GetBaseUrl(),
                "absolute_url" => CoreHelper::GetAbsoluteUrl(),
                "blank_pixel" => "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
            );
        });
    }

    /**
     * @param $content string template to parse
     * @param null $placeholders extra placeholder for parsing
     * @return mixed parsed template
     */
    public static function ParseTemplate($content, $placeholders = null) {

        $placeholders = array_merge($placeholders ? $placeholders : array(), self::GetDefaultPlaceholders());

        foreach($placeholders as $key => $value) {
            $content = str_replace("\${" . $key . "}", $value, $content);
        }

        return $content;
    }
}
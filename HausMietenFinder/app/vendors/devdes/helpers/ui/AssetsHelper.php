<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 09.01.15
 * Time: 23:05
 */

namespace DevDes\Helpers\UI;

use DevDes\Helpers\Core\ArraysHelper;
use DevDes\Helpers\Core\IOHelper;

class AssetsHelper {

    /**
     * @param $base_folder string base folder
     * @param $identifier string concatenated file identifier
     * @return mixed concatenated file configuration
     */
    public static function GetConcatenatedFileConfiguration($base_folder, $identifier) {

        $configuration = simplexml_load_file($base_folder . 'config.xml');

        return ArraysHelper::FindFirst($configuration->concatenated, function($el) use($identifier) {
            return $el['name'] == $identifier;
        });
    }

    /**
     * @param $identifier string concatenated files identifier
     * @param $base_folder string folders where files will be searched on
     * @return array list of files
     */
    public static function GetFilesList($identifier, $base_folder) {
        $file_properties = self::GetConcatenatedFileConfiguration($base_folder, $identifier);

        $files = array();

        foreach($file_properties->component as $component) {
            $files = array_merge($files, self::GetRelativeFilePaths($base_folder, $component));
        }

        return $files;
    }

    /**
     * @param $identifier string concatenation identifier
     * @param $base_folder string base folder for concatenation
     * @param string $glue delimiter
     * @param $parsers array list of parsers to use when reading files - according to what specified on the config.json
     * @param $pre_include string string to include at the start of the file before parsing
     * @return string get concatenated file content
     */
    public static function GetConcatenatedContent($identifier, $base_folder, $glue='', $parsers, $pre_include='') {

        $file_properties = self::GetConcatenatedFileConfiguration($base_folder, $identifier);

        $contents = array();

        foreach($file_properties->component as $component) {

            $component_files = self::GetRelativeFilePaths($base_folder, $component);

            $files_full_path = array_map(function($file) use($base_folder) {
                return $base_folder . $file;
            }, $component_files);

            $component_content = IOHelper::GetFilesContent($files_full_path, $glue);

            // Default formatting
            $component_content = TemplateFormatter::ParseTemplate($component_content);

            // Custom parsers
            $component_parsers = explode(',', $component['parsers']);

            foreach($component_parsers as $parser) {
                if(!$parser) continue;

                $component_content = $parsers[$parser]($pre_include . $component_content);
            }

            array_push($contents, $component_content);
        }

        return implode($glue, $contents);
    }

    /**
     * @param $base_folder string base local folder
     * @param $component SimpleXMLElement component part configuration from config.json
     * @return array list of relative paths
     */
    public static function GetRelativeFilePaths($base_folder, $component) {
        $component_files = array();

        foreach($component->children() as $child_node) {
            switch($child_node->getName()) {
                case 'files': {

                    foreach($child_node->children() as $element) {
                        array_push($component_files, (string)$element);
                    }

                    break;
                }
                case 'folders': {

                    foreach ($child_node->children() as $element) {
                        $component_files = array_merge($component_files, self::GetFolderContent($base_folder, (string)$element));
                    }

                    break;
                }
            }
        }

        return $component_files;
    }

    /**
     * @param $base_folder string base folder
     * @param $file string file path
     * @param $parsers array special functions to be applied to particular extensions
     * @param $pre_include string string to include at the start of the file before parsing
     * @return string file content
     */
    public static function GetAssetContent($base_folder, $file, $parsers, $pre_include='') {

        $file_content = IOHelper::GetFileContent($base_folder . $file);

        // Default formatters
        $file_content = TemplateFormatter::ParseTemplate($file_content);

        $extension = IOHelper::GetExtension($file);
        if(array_key_exists($extension, $parsers)) {
            $file_content = $parsers[$extension]($pre_include . $file_content);
        }

        return $file_content;
    }

    public static function GetAllUrls($base_folder, $identifier, $base_url, $template) {
        $files_list = self::GetFilesList($identifier, $base_folder);

        $contents = array();

        foreach($files_list as $file) {
            array_push($contents, sprintf($template, $base_url . $file));
        }

        return implode("\r\n", $contents);
    }

    /**
     * @param $base_folder string base folder
     * @param $folder string folder to search in
     * @return array all folder content (list of files), recursive
     */
    public static function GetFolderContent($base_folder, $folder) {

        $files = array();

        /* Recursion through sub folders */
        $child_elements = scandir($base_folder . $folder);

        foreach($child_elements as $child_element) {

            if($child_element[0] == '.') continue;

            $full_path = $base_folder . $folder . '/' . $child_element;

            if(is_dir($full_path)) {
                $files = array_merge(
                    $files,
                    self::GetFolderContent(
                        $base_folder,
                        $folder . '/' . $child_element
                    )
                );
            } else if(is_file($full_path)) {
                array_push($files, $folder . '/' . $child_element);
            }
        }

        return $files;
    }
}
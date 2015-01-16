<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 10.01.15
 * Time: 22:02
 */

namespace DevDes\Helpers\Core;


class IOHelper {

    /**
     * @param $file string file path
     * @return string extension in lowercase
     */
    public static function GetExtension($file) {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    /**
     * @param $files array list of files (full paths)
     * @param $glue string implode glue
     * @return string full files concatenated content
     */
    public static function GetFilesContent($files, $glue) {
        $contents = array();

        foreach($files as $file) {
            array_push($contents, self::GetFileContent($file));
        }

        return implode($glue, $contents);
    }

    /**
     * @param $file string file path
     * @return string file content
     */
    public static function GetFileContent($file) {
        return file_get_contents($file);
    }
}
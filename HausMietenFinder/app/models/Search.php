<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 14.01.15
 * Time: 13:15
 */

namespace HausMietenFinder\Models;

use DevDes\Helpers\Core\FormatingHelper;

class Search extends \Phalcon\Mvc\Collection {


	public function getSource()
    {
        return "searches";
    }

    public static function GetOrCreate($data) {

        $record = self::findFirst(array(
            array(
                'location' => $data->location,
                'type' => $data->type
            )
        ));

        if(!$record) {
            $record = new Search();
            $record->location = $data->location;
            $record->type = $data->type;
            $record->latitude = $data->latitude;
            $record->longitude = $data->longitude;
            $record->last_updated = null;

            if($record->save() == false) {
                throw new Exception("Cannot save a new search");
            }
        }

        return $record;
    }

}
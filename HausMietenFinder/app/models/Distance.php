<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 14.01.15
 * Time: 13:15
 */

namespace HausMietenFinder\Models;

class Distance extends \Phalcon\Mvc\Collection {


	public function getSource()
    {
        return "distances";
    }

}
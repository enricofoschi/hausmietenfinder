<?php

namespace HausMietenFinder\Models;

class House  extends \Phalcon\Mvc\Collection
{
    public function getSource()
    {
        return "houses";
    }

    public function getLargePictureUrl() {
        return str_replace('60x60', '300x300', $this->picture_url);
    }
    public function getUrl() {
        return 'http://www.immobilienscout24.de/expose/' . $this->immobilien24_id();
    }
}

<?php

namespace HausMietenFinder\Models;

class House  extends \Phalcon\Mvc\Collection
{
    public function getSource()
    {
        return "houses";
    }

    public function getAddressForGoogleMap() {
        $address = $this->address;
        $address_str = "";
        if($address->wgs84Coordinate) {
            $address_str = $address->wgs84Coordinate->latitude . "," . $address->wgs84Coordinate->longitude;
        } else {
            if ($address->street) {
                $address_str .= $address->street;
                if ($address->houseNumber) {
                    $address_str .= " " . $address->houseNumber;
                }
                $address_str .= ", ";
            }
            $address_str .= $address->postcode . ", ";
            if ($address->quarter) {
                $address_str .= $address->quarter . ", ";
            }
            $address_str .= $address->city;
        }
        return $address_str;
    }
}

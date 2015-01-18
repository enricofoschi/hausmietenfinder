<?php

namespace HausMietenFinder\Services;

use DevDes\Helpers\Core\TimeHelper;
use DevDes\Helpers\Core\ConfigHelper;

class GoogleMapsService {

    public function GetDistanceMinutes($house, $search) {

        /* Formatting the address */
        $address_str = $house->getAddressForGoogleMap();

        /* Building the querystring and querying google */
        $ch = curl_init();
        $gmap_curl = "https://maps.googleapis.com/maps/api/directions/json?" .
            "origin=" . urlencode($address_str) .
            "&destination=" . $search->latitude . "," . $search->longitude .
            "&sensor=false" .
            "&departure_time=" . TimeHelper::GetTicks('next tuesday, 7am') .
            "&mode=transit&alternatives=true" .
            "&key=" . ConfigHelper::GetMainConfig()["pwd"]["google_api"];

        curl_setopt($ch, CURLOPT_URL, $gmap_curl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        /* Finding out quickest route */
        if($response->routes && $response->routes[0]->legs) {

            $quickest_route = array_reduce($response->routes, function($A, $B){
                if(!$A) return $B;
                return $A->legs[0]->duration->value < $B->legs[0]->duration->value ? $A : $B;
            });

            $legs = $quickest_route->legs;
            $quickest_leg = array_reduce($legs, function($A, $B){
                if(!$A) return $B;
                return $A->duration->value < $B->duration->value ? $A : $B;
            });
            return (int)($quickest_leg->duration->value/60);
        }

        return 0;
    }
}

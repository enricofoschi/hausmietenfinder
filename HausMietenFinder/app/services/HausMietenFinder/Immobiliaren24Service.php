<?php

namespace HausMietenFinder\Services;

use HausMietenFinder\Models\House;
use HausMietenFinder\Models\Distance;
use HausMietenFinder\Models\Search;

abstract class DistanceStatus
{
    const DefaultStatus = 0;
    const Shortlisted = 1;
    const Removed = 2;
}

class Immobiliaren24Service {

    public function GetHouses($search, $page_num=1, $final_list=null)
    {
        $url = "http://rest.immobilienscout24.de/restapi/api/search/v1.0/search/"
            . "radius?realestatetype=houserent"
            . "&geocoordinates=" . $search->latitude . ";" . $search->longitude . ";75"
            . "&pageNumber=" . $page_num;

        echo('\nLoading ' . $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        /* Retrieve Houses */
        $xml_doc = simplexml_load_string($response);
        $paging = $xml_doc->children()->paging;
        $results = $xml_doc->children()->resultlistEntries->children();

        if (!$final_list) {
            $final_list = $this::ConvertXmlNodesToArray($results);
        } else {
            $final_list = array_merge($final_list, $this::ConvertXmlNodesToArray($results));
        }

        if ((int)$paging->pageNumber < (int)$paging->numberOfPages) {
            return $this->GetHouses($search, $page_num + 1, $final_list);
        } else {

            print_r(count($final_list) . ' houses found');

            /* For each house - load from Houses and find out if there already */
            foreach ($final_list as $result) {

                /* Sanitizing Simple XML to be able to read it without namespacing issues */
                $sanitized_data = str_replace('resultlist:', '', $result->asXML());
                $sanitized_data = str_replace('xlink:', '', $sanitized_data);
                $result = simplexml_load_string($sanitized_data);

                $house = House::findFirst(array(
                    array("immobilien24_id" => (int)$result->realEstateId)
                ));

                if (!$house) {
                    $house = new House();
                }
                $house->immobilien24_id = (int)$result->realEstateId;
                $house->title = (string)$result->realEstate->title;
                $house->with_kitchen = $result->realEstate->builtInKitchen == 'true';
                $house->warm_miete = (int)$result->realEstate->calculatedPrice->value;
                $house->living_space = (int)$result->realEstate->livingSpace;
                $house->exact_address = (bool)$result->realEstate->address->street;
                $house->private_offer = $result->realEstate->privateOffer == "true";
                $house->picture_url = (string)$result->realEstate->titlePicture->attributes()->href;
                $house->address = $result->realEstate->address;
                $house->address_str = $house->getAddressForGoogleMap();

                if ($house->save() == false) {
                    throw new Exception('Could not save a new house');
                }

                $this->SetTransitTime($house, $search);
            }
        }
    }

    public function SetTransitTime($house, $search) {

        $house_id = $house->getId()->{'$id'};
        $search_id = $search->getId()->{'$id'};

        $distance = Distance::findFirst(array(
            array(
                "search_id" => $search_id,
                "house_id" => $house_id
            )
        ));

        if(!$distance) {
            $distance = new Distance();
            $distance->status = DistanceStatus::DefaultStatus;
            $distance->search_id = $search_id;
            $distance->house_id = $house_id;
        }

        $distance->house = json_decode(json_encode($house));

        unset($distance->house->_id);

        $di = \Phalcon\DI::getDefault();

        $loop = 0;
        do {
            echo "\nGetting time for " . $house->title;

            if($loop) sleep(1);

            $transit_time =  $di['googlemaps_service']->GetDistanceMinutes($house, $search);
            $loop++;

        } while($loop < 3 && !$transit_time);

        if($transit_time) {
            echo "Transit time: " .$transit_time;
            $distance->transit_time = $transit_time;
        }
        
        $distance->save();
    }

    public function GetSearchResults($search_id, $page) {

        $search = Search::findById($search_id);

        $match_criteria = array(
            "search_id" => $search_id,
            "status" => array('$in' => [DistanceStatus::Shortlisted, DistanceStatus::DefaultStatus]),
            "house.with_kitchen" => true,
            "house.warm_miete" => array('$lt' => 1500)
        );

        /* Getting Distances by Page */
        $distances = Distance::find(array(
            $match_criteria,
            "sort" => array('transit_time' => 1),
            "limit" => 12,
            "skip" => ($page-1) * 12
        ));

        /* Getting general info about available Results */
        $aggregate_info = Distance::aggregate(array(
            array(
                '$match' => $match_criteria
            ),
            array(
                '$group' => array(
                    '_id'=> null,
                    'count' => array( '$sum' => 1 )
                )
            )
        ));

        $retVal = new \stdClass();
        $retVal->distances = $distances;
        $retVal->total_count = $aggregate_info['result'][0]['count'];
        $retVal->search = $search;

        return $retVal;
    }

    public function ChangeDistanceStatus($distance_id, $remove) {

        $distance = Distance::findById($distance_id);

        if($distance) {
            $distance->status = $remove ? DistanceStatus::Removed : DistanceStatus::Shortlisted;
            $distance->save();
        } else {
            die("No house found for " . $distance_id);
        }
    }

    private static function ConvertXmlNodesToArray($xmlNodes) {

        $ret_array = array();

        foreach($xmlNodes as $node) {
            array_push($ret_array, $node);
        }

        return $ret_array;
    }
}

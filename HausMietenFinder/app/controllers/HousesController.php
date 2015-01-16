<?php

use HausMietenFinder\Models\Search;
use DevDes\Helpers\Core\RedisHelper;

class HousesController extends ControllerBase
{

    public function viewAction() {
        $this->view->houses = $this->immobiliaren24_service->GetBestHouses();
    }

    public function updateDecisionAction() {
        $house_id = (int)$this->request->getPost('house_id');
        $decision = (int)$this->request->getPost('decision');

        $this->view->houses = $this->immobiliaren24_service->UpdateDecision($house_id, $decision);
    }

    public function testAction() {
        $this->view->disable();

        RedisHelper::Test();
    }

    public function searchAction() {

        $this->view->disable();

        /* Retrieving or creating the search record */

        $data = $this->request->getJsonRawBody();

        $search = Search::GetOrCreate($data);

        /* If not updated recently, sending update to the task scheduler */
        $available = property_exists($search, 'last_update');

        $json_search_object = array(
            '$id' => $search->getId()->{'$id'},
            'reply_to_queue' => $data->rabbitMQQueue,
            'nodejs_client_id' => $data->client_id
        );
        $json_search_object = json_encode($json_search_object);


        if(!$available) {
            $this->rabbitmq_service->PublishMessage('hausmietenfinder.search', $json_search_object);
        }

        echo json_encode(array(
            'success' => true,
            'search_id' => $search->getId()->{'$id'},
            'available' => $available
        ));

    }
}


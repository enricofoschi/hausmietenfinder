<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 14.01.15
 * Time: 19:29
 */

$config = include __DIR__ . "/../config/config.php";
include __DIR__ . "/../config/loader.php";
include __DIR__ . "/../config/services.php";

use HausMietenFinder\Models\Search;
use DevDes\Helpers\Core\FormatingHelper;

$di = \Phalcon\DI::getDefault();
$rabbitmq_service = $di['rabbitmq_service'];
$immobiliaren24_service = $di['immobiliaren24_service'];

$rabbitmq_service->ListenToMessages(
    'hausmietenfinder.search',
    function($msg) use($rabbitmq_service, $immobiliaren24_service) {

        $msg_data = json_decode($msg->body);

        try {
            $search_id = $msg_data->{'$id'};

            $search = Search::findById($search_id);

            echo "\nGot new search";
            print_r($search_id);

            if ($search) {
                $immobiliaren24_service->GetHouses($search);
            }

            $search->last_update = FormatingHelper::GetJavascriptGMT();
            $search->save();
        }
        catch(Exception $ex) {
            echo PHP_EOL . PHP_EOL . 'Exception: ' . $ex->getMessage() . PHP_EOL;
        }

        $rabbitmq_service->PublishMessage($msg_data->reply_to_queue, $msg->body);

        echo PHP_EOL . PHP_EOL . "Message parsed";

        $rabbitmq_service->Acknowledge($msg);
});


$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}
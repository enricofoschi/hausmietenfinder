<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 14.01.15
 * Time: 19:29
 */

include __DIR__ . "/../../public/loader.php";

use HausMietenFinder\Models\Search;
use DevDes\Helpers\Core\FormatingHelper;

$di = \Phalcon\DI::getDefault();
$rabbitmq_service = $di['rabbitmq_service'];
$immobiliaren24_service = $di['immobiliaren24_service'];

/* Listening to RabbitMQ queue */
$rabbitmq_service->ListenToMessages(
    'hausmietenfinder.search',
    function($msg) use($rabbitmq_service, $immobiliaren24_service) {

        /* Encoding Data */
        $msg_data = json_decode($msg->body);

        /* Notifying the processing */
        $msg_data->processing = true;
        $rabbitmq_service->PublishMessage($msg_data->reply_to_queue, json_encode($msg_data));

        /* Triggering the search and saving the last_update field to MongoDB */
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

        /* Notifying of the end of the process */
        $msg_data->processing = false;
        $msg_data->finished = true;
        $rabbitmq_service->PublishMessage($msg_data->reply_to_queue, json_encode($msg_data));

        echo PHP_EOL . PHP_EOL . "Message parsed";

        /* Most important part: acknowledging */
        $rabbitmq_service->Acknowledge($msg);
});


$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}
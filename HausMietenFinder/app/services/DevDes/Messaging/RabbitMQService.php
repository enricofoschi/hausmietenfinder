<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 14.01.15
 * Time: 15:10
 */

namespace DevDes\Services\Messaging;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {

    protected $host;
    protected $port;
    protected $username;
    protected $password;

    public function __construct($_host, $_port, $_username, $_password) {
        $this->host = $_host;
        $this->port = $_port;
        $this->username = $_username;
        $this->password = $_password;
    }

    /**
     * @param $queue string name of the rabbit mq queue (will be created if not present) - persistent and durable
     * @param $json string message to publish
     */
    public function PublishMessage($queue, $json) {

        $connection = $this->GetConnection();
        $channel = $connection->channel();

        try {
            $this->DeclareQueue($channel, $queue);

            $msg = new AMQPMessage(
                $json,
                array('delivery_mode' => 2) # make message persistent
            );
            $channel->basic_publish($msg, '', $queue);
        }
        catch(Exception $e) {
            // log
        }

        $channel->close();
        $connection->close();
    }

    /**
     * @return AMQPConnection connection to RabbitMQ
     */
    public function GetConnection() {
        $connection = new AMQPConnection(
            $this->host,
            $this->port,
            $this->username,
            $this->password
        );

        return $connection;
    }

    /**
     * @param $msg mixed msg to acknowledge
     */
    public function Acknowledge($msg) {
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    public function DeclareQueue($channel, $queue) {
        $channel->queue_declare($queue, false, true, false, false);
    }

    public function ListenToMessages($queue, $callback) {

        $connection = $this->GetConnection();
        $channel = $connection->channel();

        try {
            $this->DeclareQueue($channel, $queue);

            echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

            $channel->basic_qos(null, 1, null);
            $channel->basic_consume($queue, '', false, false, false, false, $callback);

            while (count($channel->callbacks)) {
                $channel->wait();
            }
        }
        catch(Exception $ex) {
            // log
        }

        $channel->close();
        $connection->close();
    }

}
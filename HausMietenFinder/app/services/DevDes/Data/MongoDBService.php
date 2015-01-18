<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 16.01.15
 * Time: 21:43
 */

namespace DevDes\Services\Data;

class MongoDBService {

    protected $client;
    protected $database;

    public function __construct($_database) {
        $this->client = new \MongoClient();
        $this->database = $this->client->selectDB($_database);
    }

    public function getClient() {
        return $this->client;
    }

    public function getDatabase() {
        return $this->database;
    }
}
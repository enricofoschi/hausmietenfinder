<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 16.01.15
 * Time: 21:43
 */

namespace DevDes\Services\Data;

class RedisService {

    protected $host;
    protected $port;
    protected $db;

    public function __construct($_host, $_port, $_db) {
        $this->host = $_host;
        $this->port = $_port;
        $this->db = $_db;
    }

    public function GetConnectedClient() {
        $redis = new Redis();
        $redis->pconnect($this->host, $this->port);
        $redis->select($this->db);
        return $redis;
    }
}
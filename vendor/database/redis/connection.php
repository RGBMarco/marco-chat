<?php
    namespace Vendor\Database\Redis;
    class RedisConnection {
        public static $connection = null;
        public function __construct() {
            if (!isset($connection)) {
                self::$connection = new \Redis();
                if (!self::$connection->pconnect('127.0.0.1','6379')) {
                    throw new Exception("Redis Connect failed");
                }
            }
        }
    }
?>
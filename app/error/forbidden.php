<?php
    namespace App\Error;
    require_once(__DIR__."/../route/handler.php");
    require_once(__DIR__."../../../vendor/database/redis/connection.php");
    use Vendor\Database\Redis\RedisConnection;
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class Forbidden extends RouteHandler {
        public function __construct() {
        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $arg) {
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $str = $redis->hget('view','forbidden');
            $response->header("Content-Type","text/html");
            $response->end($str);
        }
    }
?>
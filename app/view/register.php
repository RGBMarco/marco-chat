<?php
    namespace App\View;
    require_once(__DIR__."/../route/handler.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    use App\Route\RouteHandler;
    use Vendor\Database\Redis\RedisConnection;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class Register extends RouteHandler {
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $str = $redis->hget('view','register');
            $response->header("Content-Type","text/html");
            $response->end($str);
        }
    }
?>
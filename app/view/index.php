<?php
    namespace App\View;
    require_once(__DIR__."/../route/handler.php");
    require_once(__DIR__."/../../vendor/autoload.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Route\RouteHandler;
    use Vendor\Database\Redis\RedisConnection;
    class Index extends RouteHandler{
        public function __construct() {
        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","text/html;charset=utf-8");
            /*$redis = new \Redis();
            if (!$redis->connect("127.0.0.1",6379)) {
                $response->end("页面不存在!");
                return;
            }*/
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $str = $redis->hget("view","index");
            $response->end($str);
        }
    }
?>
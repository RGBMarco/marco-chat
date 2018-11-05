<?php
    namespace App\View;
    require_once(__DIR__."/../__autoload.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class Chat extends RouteHandler {
        public function __construct() {

        }

        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","text/html");
            $redis = new \Redis();
            if (!$redis->connect("127.0.0.1",6379)) {
                $response->end("<h1>页面不存在</h1>");
            }
            $str = $redis->hget("view","chat");
            $response->end($str);
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {

        }
    }
?>
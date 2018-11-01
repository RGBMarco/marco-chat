<?php
    require_once(__DIR__."/route/handler.php");
    use App\Route\RouteHandler;
    class Test extends RouteHandler {
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->end("<h1>我已成功调用！</h1>");
        }
    }
?>
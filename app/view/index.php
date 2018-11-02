<?php
    namespace App\View;
    require_once(__DIR__."/../route/handler.php");
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Route\RouteHandler;
    class Index extends RouteHandler{
        public function __construct() {
        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","text/html;charset=utf-8");
            $user = "Marco";
            $response->end("<h1>主页</h1><img src=\"http://localhost:12000/favicon.ico\"><h1>$user<h1>");
        }
    }
?>
<?php
    namespace App\Route;
    require_once(__DIR__."/register.php");
    use RouteRegister;
    class RouteForward {
        public function __construct() {

        }
        public function forward(swoole_http_request &$request,swoole_http_response &$response) {
            $uri = $request->server['request_uri'];
            $className = "App\Route\Handler";
            $method = "get";
            $parms = [];
            if (array_key_exists($uri,\App\Route\RouteRegister::$routes)) {
                $className = \App\Route\RouteRegister::$routes[$view];
            } else {
                $regex = "\\(.+)\\(.+)";
            }
        }
    }
?>
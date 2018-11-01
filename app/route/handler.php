<?php
    namespace App\Route;
    use Swoole\Http\Request;
    use Swoole\Http\Response;
    class RouteHandler {
        public function __construct() {

        }
        /*public function get(swoole_http_request &$request,swoole_http_reponse &$response,array $parms = null) {

        }*/
        public function post(swoole_http_request $request,swoole_http_response $response,array $parms = null) {

        }
        public function patch(swoole_http_request $request,swoole_http_response $response ,array $params = null) {

        }
        public function delete(swoole_http_request $request,swoole_http_response $response,array $params = null) {

        }
    }
?>
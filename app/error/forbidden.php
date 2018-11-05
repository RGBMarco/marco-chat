<?php
    namespace App\Error;
    require_once(__DIR__."/../route/handler.php");
    class Forbidden extends RouteHandler {
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $arg) {

        }
    }
?>
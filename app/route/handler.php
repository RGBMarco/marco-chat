<?php
    namespace App\Route;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class RouteHandler {
        public const BASEURL = "http://www.rgbmarco.com:12000";
        public function __construct() {

        }
        public function get(swoole_http_request &$request,swoole_http_reponse &$response,array $parms) {

        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $parms) {

        }
        public function patch(swoole_http_request $request,swoole_http_response $response ,array $params) {

        }
        public function delete(swoole_http_request $request,swoole_http_response $response,array $params) {

        }
    }
?>
<?php
    namespace App\View;
    require_once(__DIR__."/../route/handler.php");
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Route\RouteHandler;
    class Favicon extends RouteHandler {
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","image/png");
            $filename = __DIR__."/../src/favicon.png";
            $size = filesize($filename);
            $handle = fopen($filename,"r");
            $response->end(fread($handle,$size));
        }
    }
?>
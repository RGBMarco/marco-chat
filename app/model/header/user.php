<?php
    namespace App\Model\Header;
    require_once(__DIR__."/../../route/handler.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;

    class UserHeader extends RouteHandler {
        public function __construct() {

        }

        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if(!isset($args['id'])) {

            }
            $response->header("Content-Type","image/png");
            $response->end(file_get_contents(__DIR__."/user.png"));
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {

        }
    }
?>
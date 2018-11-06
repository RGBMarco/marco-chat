<?php
    namespace App\Route;
    require_once(__DIR__."/entry.php");
    use App\Route\RouteEntry;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class RouteForward {
        public function __construct() {

        }
        public static function forward(swoole_http_request $request) {
            $uri = $request->server['request_uri'];
            $method = strtolower($request->server["request_method"]);
            echo $uri . "  " . $method . "\n";
            return new RouteEntry($uri,$method);
        }
    }
?>
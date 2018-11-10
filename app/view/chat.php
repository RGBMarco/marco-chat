<?php
    namespace App\View;
    require_once(__DIR__."/../__autoload.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    require_once(__DIR__."/../session/usersession.php");
    use Vendor\Database\Redis\RedisConnection;
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Session\UserSession;
    class Chat extends RouteHandler {
        public function __construct() {

        }

        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","text/html");
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $str = $redis->hget("view","chat");
            var_dump($args);
            $userSession = new UserSession();
            $userSession->post($request,$response,[]);
            $response->end($str);
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {

        }
    }
?>
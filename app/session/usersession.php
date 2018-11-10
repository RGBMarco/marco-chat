<?php
    namespace App\Session;
    require_once(__DIR__."/../route/handler.php");
    //require_once(__DIR__."/../error/expire.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    
    use App\Route\RouteHandler;
    //use App\Error\Expire;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use Vendor\Database\Redis\RedisConnection;

    class UserSession extends RouteHandler {
        //过期时间为 4小时
        //Redis db1 存储 UserSession
        public const USER_SESSION_COOKIE_KEY = "user-session-cookie";
        public function __consturct() {
        }
        //生成用户会话 //只能内部访问不提供API接口
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $email = $args["email"];
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $redis->select(1);
            $session_token = md5(uniqid(microtime(true),true));
            $redis->set($email,$session_token);
            $redis->expire($email,3600 * 4);
            $response->cookie(self::USER_SESSION_COOKIE_KEY,$session_token,time() + 3600 * 4,'/');
            return;
        }
        //验证用户会话
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($request->cookie[self::USER_SESSION_COOKIE_KEY])) {
                echo "未设置!\n";
                return false;
            }
            $userSessionToken = $request->cookie[self::USER_SESSION_COOKIE_KEY];
            echo $userSessionToken . "\n";
            return;
        }
    }
?>
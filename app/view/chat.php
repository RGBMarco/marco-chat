<?php
    namespace App\View;
    require_once(__DIR__."/../__autoload.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    require_once(__DIR__."/../session/usersession.php");
    require_once(__DIR__."/../error/forbidden.php");
    require_once(__DIR__."/../../vendor/database/postgressql/sqlquery.php");
    require_once(__DIR__."/../../vendor/autoload.php");
    use Vendor\Database\Redis\RedisConnection;
    use Vendor\Database\Postgressql\SqlQuery;
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Session\UserSession;
    use App\Error\Forbidden;
    class Chat extends RouteHandler {
        public function __construct() {

        }

        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","text/html");
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $redis->select(0);
            $str = $redis->hget("view","chat");
            $userSession = new UserSession();
            $pass = $userSession->post($request,$response,$args);
            if (!$pass) {
                $forbidden = new Forbidden();
                $forbidden->get($request,$response,[]);
                return;
            }
            $redis->select(1);
            $data = $redis->get($args['id']);
            $value = \json_decode($data);
            $arr = [];
            $arr['userName'] = $this->getUserName($value->email);
            $m = new \Mustache_Engine();
            $response->end($m->render($str,$arr));
        }
        public function getUserName(string $email):string {
            $q = "SELECT getUserName('$email')";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                return $result['data'][0]['getusername'];
            }
            return " ";
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {

        }
    }
?>
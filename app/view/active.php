<?php
    namespace App\View;
    require_once(__DIR__."/../route/handler.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    require_once(__DIR__."/../../vendor/database/postgressql/sqlquery.php");
    require_once(__DIR__."/../../vendor/autoload.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use Vendor\Database\Redis\RedisConnection;
    use Vendor\Database\Postgressql\SqlQuery;
    //Mustache_Autoloader::register();
    class ActiveUser extends RouteHandler {
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $fail = [
                'activeHeader' => "激活失败",
                'activeHint'   => "系统故障,请稍候再试!",
                'activeAction' => "重新注册",
            ];
            $success = [
                'activeHeader' => "激活成功",
                'activeHint'   => "激活成功,请前去登录",
                'activeAction' => "前去登录",
            ];
            $rc = new RedisConnection();
            $redis = RedisConnection::$connection;
            $str = $redis->hget('view','active');
            $m = new \Mustache_Engine();
            if (!isset($request->get["t"]) || !isset($request->get["e"])) {
                $response->status(200);
                $response->end($m->render($str,$fail));
                return;
            }
            $activeToken = $request->get["t"];
            $email = $request->get["e"];
            if (!$this->active($email,$activeToken)) {
                $response->status(200);
                $response->end($m->render($str,$fail));
                return;
            }
            $response->status(200);
            $response->end($m->render($str,$success));
        }
        public function active(string $email,string $token) {
            $sq = new SqlQuery(null);
            $q = "SELECT activeUser('$email','$token')";
            $result = $sq->query($q);
            print_r($result);
            if ($result['success']) {
                if (isset($result['data'])) {
                    if ($result['data'][0]['activeuser'] === 't') {
                        return true;
                    }
                }
            }
            return false;
        }
    }
?>
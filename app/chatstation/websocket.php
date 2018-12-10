<?php
    namespace App\ChatStation;
    require_once(__DIR__."/connection.php");
    require_once(__DIR__."/../model/user/util.php");
    require_once(__DIR__."/../../vendor/database/redis/connection.php");
    require_once(__DIR__."/../model/message/util.php");
    use Swoole\WebSocket\Server as swoole_websocket_server;
    use App\ChatStation\ChatConnection;
    use App\Model\User\Util\UserInfo;
    use Vendor\Database\Redis\RedisConnection;
    use App\Model\Message\Util\MessageUtil;
    //数组无法使用,采用Redis存储数组
    //使用索引2
    class WebSocket {
        use UserInfo;
        use MessageUtil;
        private $ws;
        public function __construct($server) {
            $this->ws = $server->listen('0.0.0.0',13000,SWOOLE_SOCK_TCP);
            $this->ws->set([
                'open_websocket_protocol'   => true,
            ]);
            $this->ws->on('open',function(swoole_websocket_server $server,$request) {
               //var_dump($server);
               echo "websocket开始!";
            });
            $redisConn = new RedisConnection();
            $redis = RedisConnection::$connection;
            $redis->select(2);
            $redis->del("websocketconns");
            $handout = function(swoole_websocket_server $server,$frame) {
                $request = \json_decode($frame->data);
                if (!$this->isBaseRequest($request)) {
                    //to do
                    echo "It's not base request!";
                    return;
                }
                if (\method_exists($this,$request->request)) {
                    $this->{$request->request}($server,$frame,$request);
                }
            };


            $this->ws->on('message',$handout);
            $this->ws->on('close',function($server,$fd) {
                $redisConn = new RedisConnection();
                $redis = RedisConnection::$connection;
                $redis->hdel("websocketconns",$fd);
                echo "$fd 已关闭!";
            });
        }
        public function isBaseRequest($request):bool {
            return \property_exists($request,'request') && \property_exists($request,'data');
        }
        public function init($server,$frame,$request) {
            echo "开始初始化!\n";
            $data = $request->data;
            if (!$this->checkInitData($data)) {
                echo "error in init";
                return;
            }
            $connection = new ChatConnection($data->userId);
            $objStr = serialize($connection);
            $redisConn = new RedisConnection();
            $redis = RedisConnection::$connection;
            $redis->select(2);
            $redis->hset("websocketconns",$frame->fd,$objStr);
            return;
        }
        public function checkInitData($data) {
            return \property_exists($data,'userId');
        }

        public function message($server,$frame,$request) {
            $message = $request->data;
            $str = \json_encode($message);
            echo $str . "\n";
            $success = $this->updateSingleSessionMessage($message->firstId,$message->secondId,$str);
            if ($success) {
                echo "插入成功!\n";
            } else {
                echo "插入失败!\n";
            }
            $firstName = $this->getUserNameById($message->firstId);
            $secondName = $this->getUserNameById($message->secondId);
            $message->firstName = $firstName;
            $message->secondName = $secondName;
            $request->message = $message;
            /*foreach ($this->ws->connections as $fd) {
                echo "有 $fd\n";
            }*/
            $redisConn = new RedisConnection();
            $redis = RedisConnection::$connection;
            $redis->select(2);
            $allconnections = $redis->hgetall("websocketconns");
            $singleconnections = [];
            foreach ($allconnections as $k => $v) {
                $singleconnections[$k] = \unserialize($v);
            }
            foreach ($singleconnections as $k => $v) {
                if ($v->haveCareSession($message->sessionId)) {
                    $server->push($k,\json_encode($request));
                }
            }
            return;
        }
        public function __destruct() {
           //清空所有记录

        }
       
    }
?>
<?php
    namespace App\ChatStation;
    require_once(__DIR__."/connection.php");
    require_once(__DIR__."/../model/user/util.php");
    use Swoole\WebSocket\Server as swoole_websocket_server;
    use App\ChatStation\ChatConnection;
    use App\Model\User\Util\UserInfo;
    class WebSocket {
        use UserInfo;
        private $ws;
        private static $connections = [];
        public function __construct($server) {
            $this->ws = $server->listen('0.0.0.0',13000,SWOOLE_SOCK_TCP);
            $this->ws->set([
                'open_websocket_protocol'   => true,
            ]);
            $this->ws->on('open',function(swoole_websocket_server $server,$request) {
               //var_dump($server);
               echo "websocket开始!";
            });
            $colusure = function(swoole_websocket_server $server,$frame) {
                $request = \json_decode($frame->data);
                //var_dump($request);
                if (!$this->isBaseRequest($request)) {
                    //to do
                    echo "It's not base request!";
                    return;
                }
                if (\method_exists($this,$request->request)) {
                    $this->{$request->request}($server,$frame,$request);
                    //var_dump(self::$connections);
                };
            };
            $this->ws->on('message',$colusure->bindTo($this)
            );
            $this->ws->on('close',function($server,$fd) {
                //echo $fd;
                echo "before close: \n";
                var_dump(self::$connections);
                unset(self::$connections[$fd]);
                echo "end close: \n";
                var_dump(self::$connections);
            });
        }
        public function isBaseRequest($request):bool {
            return \property_exists($request,'request') && \property_exists($request,'data');
        }
        public function init($server,$frame,$request) {
            var_dump(self::$connections);
            $data = $request->data;
            if (!$this->checkInitData($data)) {
                echo "error in init";
                return;
            }
            $connection = new ChatConnection($data->userId);
            //var_dump(self::$connections);
            self::$connections[$frame->fd] = $connection;
            //var_dump(self::$connections);
            return;
        }
        public function checkInitData($data) {
            return \property_exists($data,'userId');
        }

        public function message($server,$frame,$request) {
            $message = $request->data;
            $firstName = $this->getUserNameById($message->firstId);
            $secondName = $this->getUserNameById($message->secondId);
            $message->firstName = $firstName;
            $message->secondName = $secondName;
            $request->message = $message;
            //var_dump($this);
            //var_dump(self::$connections);
            foreach (self::$connections as $k => $v) {
                echo $k;
                //var_dump(self::$connections);
                if ($v->haveCareSession($message->sessionId)) {
                    $server->push($k,\json_encode($request));
                    //echo "have forward!";
                }
            }
            return;
        }
    }
?>
<?php
    namespace App\ChatStation;
    require_once(__DIR__."/connection.php");
    require_once(__DIR__."/../model/user/util.php");
    use Swoole\WebSocket\Server as swoole_websocket_server;
    use App\ChatStation\ChatConnection;
    use App\Model\User\Util\UserInfo;
    $globalconnections = [];
    class WebSocket {
        use UserInfo;
        private $ws;
        private $singleconnections;
        public function __construct($server,&$useconnections) {
            $this->ws = $server->listen('0.0.0.0',13000,SWOOLE_SOCK_TCP);
            $this->ws->set([
                'open_websocket_protocol'   => true,
            ]);
            $this->singleconnections = $useconnections;
            $this->ws->on('open',function(swoole_websocket_server $server,$request) {
               //var_dump($server);
               echo "websocket开始!";
            });
            
            $handout = function(swoole_websocket_server $server,$frame) {
                var_dump($this);
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
                //echo $fd;
                /*echo "before close: \n";
                var_dump(self::$connections);
                unset(self::$connections[$fd]);
                echo "end close: \n";
                var_dump(self::$connections);*/
            });
        }
        public function isBaseRequest($request):bool {
            return \property_exists($request,'request') && \property_exists($request,'data');
        }
        public function init($server,$frame,$request) {
            echo "开始初始化!\n";
            print_r($this->singleconnections);
            $data = $request->data;
            if (!$this->checkInitData($data)) {
                echo "error in init";
                return;
            }
            $connection = new ChatConnection($data->userId);
            $this->singleconnections[$frame->fd] = $connection;
            global $globalconnections;
            $globalconnections[$frame->fd] = $connection;
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
            /*foreach ($this->ws->connections as $fd) {
                echo "有 $fd\n";
            }*/
            global $globalconnections;
            var_dump($globalconnections);
            foreach ($this->singleconnections as $k => $v) {
                if ($v->haveCareSession($message->sessionId)) {
                    $server->push($k,\json_encode($request));
                }
            }
            return;
        }
        public function __destruct() {
            echo "WebSocket Ending....!\n";
        }
       
    }
?>
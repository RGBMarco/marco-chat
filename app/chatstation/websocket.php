<?php
    namespace App\ChatStation;
    require_once(__DIR__."/connection.php");
    use Swoole\WebSocket\Server as swoole_websocket_server;
    class WebSocket {
        private $ws;
        private $connections;
        public function __construct($server) {
            $this->ws = $server->listen('0.0.0.0',13000,SWOOLE_SOCK_TCP);
            $this->ws->set([
                'open_websocket_protocol'   => true,
            ]);
            $this->ws->on('open',function(swoole_websocket_server $server,$request) {
               // var_dump($server);
               echo "websocket开始!";
            });
            $this->ws->on('message',function(swoole_websocket_server $server,$frame) {
                $request = \json_decode($frame->data);
                if (!$this->isBaseRequest($requst)) {
                    //to do
                    return;
                }
                if (\method_exists($this,$request->request)) {
                    return $this->{$reuqest->request}($request);
                }
            });
            $this->ws->on('close',function($server,$fd) {
                echo $fd;
            });
        }
        public function isBaseRequest($request):bool {
            return isset($request->request) && isset($request->data);
        }
        public function init($request) {
            echo "In Init!";
        }
    }
?>
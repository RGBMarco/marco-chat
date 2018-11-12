<?php
    require_once(__DIR__."/connection.php");
    use Swoole\Websocket\Server as swoole_wbesocket_server;
    class WebSocket {
        private $server;
        private $connections;
        public function __construct() {
            $this->server =  new swoole_websocket_server('0.0.0.0',666);
            $this->server->on('open',function(swoole_websocket_server $server,$request) {
                var_dump($request);
            });
            $this->server->on('message',function(swoole_websocket_server $server,$frame) {
                echo $frame->data;
            });
            $this->server->on('close',function($server,$fd) {
                echo $fd;
            });
        }
    }
?>
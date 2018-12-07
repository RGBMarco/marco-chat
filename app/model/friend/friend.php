<?php
    namespace App\Model\Friend;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/util.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Model\Friend\Util\FriendUtil;
    class Friend extends RouteHandler {
        use FriendUtil;
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($args['id'])) {

            }
            $id = (int)$args['id'];
            $members = $this->getFriendsById($id);
            $send = [
                'data'      => $members,
                'success'   => true,   
            ];
            $sendStr = \json_encode($send);
            $response->end($sendStr);
            return;
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {

        }
    }
?>
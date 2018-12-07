<?php
    namespace App\Model\Friend;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/util.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Model\Friend\Util\FriendUtil;
    class FindFriend extends RouteHandler {
        use FriendUtil;
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            echo "查找好友!";
            if (!isset($args['id']) || !isset($request->get['query'])) {
                return;
            }
            $id = (int)$args['id'];
            $q = $request->get['query'];
            $friends = $this->findFriend($q);
            $send = [
                'success'   => true,
                'data'      => $friends,
            ];
            $sendStr = \json_encode($send);
            $response->end($sendStr);
            return;
        }
    }
?>
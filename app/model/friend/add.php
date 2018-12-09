<?php
    namespace App\Model\Friend;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/util.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Model\Friend\Util\FriendUtil;
    class AddFriend extends RouteHandler {
        use FriendUtil;
        public function __construct() {

        }

        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($args['id']) || !isset($request->get['msg']) || !isset($request->get['email'])) {
                return;
            }
            $id = (int)$args['id'];
            $rEmail = $request->get['email'];
            $msg = $request->get['msg'];
            $success = $this->requestToNewFriend($id,$rEmail,$msg);
            $send = [
                'success'       =>  $success,
            ];
            $sendStr = \json_encode($send);
            $response->end($sendStr);
            return;
        }
    }
?>
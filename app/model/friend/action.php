<?php
    namespace App\Model\Friend;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/util.php");
    use App\Route\RouteHandler;
    use App\Model\Friend\Util\FriendUtil;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;

    class FriendAction extends RouteHandler {
        use FriendUtil;
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($args['id'])) {
                return;
            }
            $id = (int)$args['id'];
            $data = $this->getNewFriendCache($id);
            print_r($data);
            $send = [
                'success'   => true,
                'data'      => $data,
            ];
            $sendStr = \json_encode($send);
            $response->end($sendStr);
            return;
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($args['id'])) {
                return;
            }
            $data = \json_decode($request->rawContent());
            var_dump($data);
            $peerId = (int)$data->data->peerId;
            $id = (int)$args['id'];
            $state = $this->confirmToNewFriend($id,$peerId);
            $send = [
               'success'    => $state,
            ];
            $sendStr = \json_encode($send);
            $response->end($send);
            return;
        }
    }
?>
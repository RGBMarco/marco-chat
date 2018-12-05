<?php
    namespace App\Model\Header;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/../user/util.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Model\User\Util\UserInfo;
    class UserHeader extends RouteHandler {
        use UserInfo;
        public function __construct() {

        }

        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if(!isset($args['id'])) {
            }
            $id = (int)$args['id'];
            echo $id;
            $exist = $this->queryUserInfoHeader($id);
            var_dump($exist);
            if ($exist) {
                $header = $this->getUserInfoHeaderById($id);
                if (!is_null($header)) {
                    if (isset($header['src']) && isset($header['type'])) {
                        $response->header("Content-Type",$header['type']);
                        $response->end(pg_unescape_bytea($header['src']));
                        echo "图片!";
                        return;
                    }
                }
            }
            else {
                $response->header("Content-Type","image/png");
                $response->end(file_get_contents(__DIR__."/user.png"));
            }
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {

        }
    }
?>
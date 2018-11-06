<?php
    namespace App\Model\User;
    require_once(__DIR__."/../../__autoload.php");
    require_once(__DIR__."/../../validate/user.php");
    require_once(__DIR__."/../../route/handler.php");
    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class User extends RouteHandler {
        private $email_;
        private $name_;
        private $id_;
        private $password_;
        private $last_ip_;
        private $create_time_;
        private $last_time_;
        private $actived_;
        private $remebered_;
        private $remember_token_;
        public function __construct() {

        }
        //用户登录
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
        }
        //用户注册
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {
            $email;
            $password;
            echo "注册\n";
            print_r($request->post);
            var_dump($request->post);
            echo $request->rawContent();
            if (isset($request->post["email"]) && isset($request->post["password"])) {
                $email = $request->post["email"];
                $password = $request->post["password"];
                echo "Register Email: " . $email . "\n";
                echo "Register Password: " . $password . "\n";
            } else {
                //Bad Request
            }
            $response->status(200);
            $response->end();
        }
        public function __destruct() {

        }
    }
?>
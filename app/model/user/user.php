<?php
    namespace App\Model\User;
    require_once(__DIR__."/../../__autoload.php");
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
        public function get(swoole_http_request $request,swoole_http_response $response,$args) {

        }
        public function post(swoole_http_request $request,swoole_http_response $response,$args) {
        }
        public function __destruct() {

        }
    }
?>
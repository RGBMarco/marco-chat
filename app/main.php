<?php
   require_once(__DIR__."/route/register.php");
   require_once(__DIR__."/route/forward.php");
   require_once(__DIR__."/view/index.php");
   require_once(__DIR__."/view/favicon.php");
   require_once(__DIR__."/utils/resource.php");
   require_once(__DIR__."/../cache/cache.php");
   require_once(__DIR__."/view/chat.php");
   require_once(__DIR__."/view/register.php");
   require_once(__DIR__."/model/user/user.php");
   require_once(__DIR__."/view/active.php");
   require_once(__DIR__."/model/header/user.php");
   require_once(__DIR__."/model/message/record.php");
   require_once(__DIR__."/chatstation/websocket.php");
   require_once(__DIR__."/model/user/userinfo.php");
   use App\Route\RouteRegister;
   use App\Route\RouteForward;
   use App\View\Index;
   use App\View\Favicon;
   use App\View\Chat;
   use App\View\Register;
   use App\View\ActiveUser;
   use App\Utils\Resource;
   use Cache\Cache;
   use App\Model\User\User;
   use App\Model\Header\UserHeader;
   use App\Model\Message\MeesageRecord;
   use App\Model\User\UserInfo;
   use App\ChatStation\WebSocket;
   $cache = new Cache();
   RouteRegister::add("/","App\View\Index");
   RouteRegister::add("/utils/{name}","App\Utils\Resource");
   RouteRegister::add("/favicon.ico","App\View\Favicon");
   RouteRegister::add("/chat/{id}","App\View\Chat");
   RouteRegister::add("/user","App\Model\User\User");
   RouteRegister::add("/register","App\View\Register");
   RouteRegister::add("/active","App\View\ActiveUser");
   RouteRegister::add("/userheader/{id}","App\Model\Header\UserHeader");
   RouteRegister::add("/message/record/{id}","App\Model\Message\MessageRecord");
   RouteRegister::add("/userinfo/{id}","App\Model\User\UserInfo");
   $server = new swoole_websocket_server("0.0.0.0",12000);
   //$http = $server->listen("0.0.0.0",12000,SWOOLE_SOCK_TCP);
   $server->set([
       'open_websocket_protocol'    => false,
       'package_max_length'         => 5 * 1024 * 1024,
   ]);
   $server->on("Request",function(swoole_http_request $request,swoole_http_response $response) {
        $entry = RouteForward::forward($request);
        $entry->run($request,$response);
   });
   $server->on("message",function($request,$response) {});
   $server->on("receive",function($request,$response) {});
   $websocket = new WebSocket($server);
   $server->start();
?>
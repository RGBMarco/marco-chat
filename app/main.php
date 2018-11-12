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
   $cache = new Cache();
   RouteRegister::add("/","App\View\Index");
   RouteRegister::add("/utils/{name}","App\Utils\Resource");
   RouteRegister::add("/favicon.ico","App\View\Favicon");
   RouteRegister::add("/chat/{id}","App\View\Chat");
   RouteRegister::add("/user","App\Model\User\User");
   RouteRegister::add("/register","App\View\Register");
   RouteRegister::add("/active","App\View\ActiveUser");
   RouteRegister::add("/userheader/{id}","App\Model\Header\UserHeader");
   $http = new swoole_http_server("0.0.0.0",12000);
   $http->on("Request",function(swoole_http_request $request,swoole_http_response $response) {
        $entry = RouteForward::forward($request);
        $entry->run($request,$response);
   });
   $http->start();
?>
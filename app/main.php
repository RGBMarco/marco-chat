<?php
   require_once(__DIR__."/route/register.php");
   require_once(__DIR__."/route/forward.php");
   require_once(__DIR__."/view/index.php");
   require_once(__DIR__."/view/favicon.php");
   require_once(__DIR__."/utils/resource.php");
   require_once(__DIR__."/../cache/cache.php");
   use App\Route\RouteRegister;
   use App\Route\RouteForward;
   use App\View\Index;
   use App\View\Favicon;
   use App\Utils\Resource;
   use Cache\Cache;
   $cache = new Cache();
   RouteRegister::add("/","App\View\Index");
   RouteRegister::add("/utils/{name}","App\Utils\Resource");
   RouteRegister::add("/favicon.ico","App\View\Favicon");
   $http = new swoole_http_server("0.0.0.0",12000);
   $http->on("Request",function(swoole_http_request $request,swoole_http_response $response) {
        $entry = RouteForward::forward($request);
        $entry->run($request,$response);
   });
   $http->start();
?>
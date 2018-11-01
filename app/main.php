<?php
   require_once(__DIR__."/route/register.php");
   require_once(__DIR__."/Test.php");
   require_once(__DIR__."/route/forward.php");
   use App\Route\RouteRegister;
   use App\Route\RouteForward;
   RouteRegister::add("/home/marco","Test");
   RouteRegister::add("/home","Test");
   $http = new swoole_http_server("0.0.0.0",12000);
   $http->on("Request",function(swoole_http_request $request,swoole_http_response $response) {
        $entry = RouteForward::forward($request);
        $entry->run($request,$response);
   });
   $http->start();
?>
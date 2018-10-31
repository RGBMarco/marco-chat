<?php
    $server = new swoole_http_server("0.0.0.0","80");
    $server->on("Request",function($request,$response) {
        //print_r($request->get);
         //print_r($request->post);
       //print_r($request->header);
        print_r($request->server);
       // print_r($request->cookie);
       // print_r($request->files);
        $response->end("<h1>Hello</h1>");
    });
   /* $server->on("close",function() {

    });*/
    $server->start();
?>
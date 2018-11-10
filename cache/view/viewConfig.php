<?php
    namespace Cache\View;
    class ViewConfig {
        public static $viewCache = [
            'index'         => __DIR__."/../../public/html/index/index.php",
            'chat'          => __DIR__."/../../public/html/chat/chat.html",
            'register'      => __DIR__."/../../public/html/register/register.html",
            'active'        => __DIR__."/../../public/html/active/active.html",
            'forbidden'     => __DIR__."/../../public/html/error/forbidden.html", 
        ];
    }
?>
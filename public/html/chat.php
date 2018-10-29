<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="app.css?version=123">
    <script type="text/javascript"  src="app.js"></script>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-sm bg-info">    
                <div class="container">
                    <div class="navbar-header">
                        <span class="navbar-brand">WeChat</span>
                    </div>
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="#">聊天</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">动态</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">广场</a></li>
                    </ul>
                    <div class="dropdown">
                        <div class="data-toggle" data-toggle="dropdown">
                            <img src="#" alt="头像">
                            <span>用户名</span>
                        </div>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item"><a class="dropdown-link">个人中心</a></li>
                            <li class="dropdown-item"><a class="dropdown-link">退出</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
    </header>
    <main>
        <div class="container">
                <div class="col col-4 offset-1">    
                   <?php
                        include("_contacts-panel.php");
                   ?>
                </div>
                <?php
                    include("message-session.php");
                ?>
        </div>
    </main>
    </body>
    
    <script>
        $('.contacts-group-user').on('click',function() {
            $($(this).siblings('.collapse').get(0)).toggle();
        });
    </script>
</html>
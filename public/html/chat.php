<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
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
                    <div id="message-panel" class="card">
                        <div class="card-header">
                            <a><img src="#" alt="联系人"></a>
                            <h4>消息</h4>
                            <a><h4>+</h4></a>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="row message-contact">
                                        <div class="col col-1">
                                            <img class="user-header" src="#" alt="">
                                        </div>
                                        <div class="col message-contact-info">
                                            <p><span>联系人</span><small>时间</small></p>
                                            <p><small>消息</small><span class="badge badge-danger">999</span></p>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                    include('_list.php');
                                ?>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <div><span>消息</span></div>
                            <div><span>联系人</span></div>
                            <div>我的</div>
                        </div>
                    </div>
                </div>
        </div>
    </main>
    </body>
</html>
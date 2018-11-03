<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="app.css?version=123">
    <script type="text/javascript"  src="app.js"></script>
    </head>
    <body>
            <!--导航栏-->
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
                    <!-- 用户操作面板 -->
                <div class="col col-4 offset-1">
                            <!--联系人-->
                    <div id= "contacts-card-panel" class="card">
                        <div class="card-header">
                            <img src="#" class="user-header">
                            <div>
                                <h4>心跳呼吸正常</h4>
                                <p><span>个人签名</span></p>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="contacts-groups">
                                <li class="contacts-group">
                                    <a class="contacts-group-user" data-toggle="collapse">我的站主</a>
                                        <ul class="collapse">
                                            <li class="user">
                                                <div class="user-title">
                                                    <img src="#" alt="头像" class="user-header">
                                                </div>
                                                <div class="user-info">
                                                    <h5>我的站主<h5>
                                                    <p><small>个性签名</small></p>
                                                </div>
                                            </li>
                                        </ul>
                                </li>
                                <li class="contacts-group">
                                    <a class="contacts-group-user" data-toggle="collapse">我的好友</a>
                                    <ul class="collapse">
                                        <li class="user">
                                            <img src="#" alt="头像" class="user-header">
                                            <div class="user-info">
                                                <h5>我的好友</h5>
                                                <p><span>个性签名</span></p>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <div class="card-footer-tab"><span>消息</span></div>
                            <div class="card-footer-tab"><span>联系人</span></div>
                            <div class="card-footer-tab"><span>我的<span></div>
                        </div>
                    </div>
                            <!--消息-->
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
                            <div class="card-footer-tab"><span>消息</span></div>
                            <div class="card-footer-tab"><span>联系人</span></div>
                            <div class="card-footer-tab"><span>我的<span></div>
                        </div>
                    </div>
                </div>
                    <!-- 聊天会话面板 -->
                <div id="message-session" class="col">
                    <div class="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- 单人会话 头部-->
                                <div id="single-session-header" class="modal-header">
                                    <div class="modal-header-title">
                                        <div class="title-info">
                                            <img src="#" alt="头像">    
                                            <h4>会话名</h4>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>      
                                    </div>
                                    <div class="modal-header-info">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a class="nav-link">聊天</a></li>
                                            <li class="nav-item"><a class="nav-link">设置</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- 多人对话 头部-->
                                <div id="multi-session-header" class="modal-header">
                                    <div class="modal-header-info">
                                        <h4 class="modal-title">会话名</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-header-nav">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item" onclick="listenClickChat()"><a class="nav-link">聊天</a><li>
                                            <li class="nav-item" onclick="listenClickNotices()"><a class="nav-link">公告</a></li>
                                            <li class="nav-item" onclick="listenClickMembers()"><a class="nav-link">所有成员</a></li>
                                            <li class="nav-item" onclick="listenClickSetting()"><a class="nav-link">设置</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- 单人， 多人聊天消息界面 -->
                                <div id="chat-modal-body" class="modal-body">
                                    <div class="modal-body-session">        
                                        <ul class="list-group">
                                            <li class="list-group-item message-by-other">
                                                <div class="message-owner">    
                                                    <img src="#" alt="头像" class="user-header">
                                                    <p><h5><span>活跃度<span><span>用户名<span></h5></p>
                                                </div>
                                                <div class="message-content">
                                                    <span>用户消息用户消息用户消息用户消息用户消息用户消息用户消息用户消息用户消息用户消息用户消息用户消息</span>
                                                </div>
                                            </li>
                                            <?php
                                                include('_chat-messages.php');
                                            ?>
                                        <ul>
                                    </div>
                                </div>
                                <!-- 单人，多人聊天脚注 -->
                                <div id="chat-modal-footer" class="modal-footer">
                                    <div class="modal-footer-toolbox">
                                        <ul class="modal-footer-toolbox-group">
                                            <li class="modal-footer-toolbox-group-item">工具1</li>
                                            <li class="modal-footer-toolbox-group-item">工具2</li>
                                            <li class="modal-footer-toolbox-group-item">更多工具</li>
                                        <ul>
                                    </div>
                                    <div class="modal-footer-message-area">
                                        <textarea id="message-input">
                                        </textarea>
                                        <div class="message-submit">
                                            <button type="button" onclick="listenInputMessage()" class="btn-info">发送</button>
                                        </div>
                                    </div>
                                </div>                
                                <!--群公告-->
                                <div id="notices-panel-body" class="modal-body">
                                    <ul class="notices">
                                        <li class="notice">
                                            <div class="notice-title">
                                                <h5>公告标题</h5>
                                                <p class="notice-title-info"><span><small>作者 日期</small></span><span><small>已读人数</small></span></p>
                                            </div>
                                            <hr>
                                            <div class="notice-content">
                                                <span>公告内容<span>
                                            </div>
                                            <div class="notice-footer">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!--群成员-->
                                <div id="member-panel-body" class="modal-body">
                                    <div clas="member-panel-content">
                                        <ul class="member-group">
                                            <li class="member-group-item">
                                                <img src="#" alt="头像" src="user-header">
                                                <p><span>群员ID</span><span>群员身份</span></p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </main>
    </body>
    <script>
        $('.contacts-group-user').on('click',function() {
            $($(this).siblings('.collapse').get(0)).toggle();
        });
    </script>
</html>
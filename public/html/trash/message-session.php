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
                <div class="modal-footer">
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
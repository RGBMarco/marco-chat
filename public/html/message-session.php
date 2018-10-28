<div id="message-session" class="col">
    <div class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                        <div class="modal-header-info">
                            <h4 class="modal-title">会话名</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-header-nav">
                            <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link">聊天</a><li>
                            <li class="nav-item"><a class="nav-link">公告</a></li>
                            <li class="nav-item"><a class="nav-link">所有成员</a></li>
                            <li class="nav-item"><a class="nav-link">设置</a></li>
                            </ul>
                        </div>
                </div>
                <div class="modal-body">
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
                            <button type="button" class="btn-info">发送</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
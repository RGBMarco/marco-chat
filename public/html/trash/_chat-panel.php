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
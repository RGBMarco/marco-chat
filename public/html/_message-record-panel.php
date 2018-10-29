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
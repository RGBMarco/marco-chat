import $ from 'jquery';
import {Config} from './config';
import mustache from 'mustache';
//import { panel } from './panel';
export default class Contacts extends Config {
    constructor(siteOwnerId,myFriendId,newFriendId,addContact) {
        super();
        this.userId_ = Number(sessionStorage.getItem('id'));
        console.log(this.userId_);
        this.siteOwnerId_ = siteOwnerId;
        this.myFriendId_ = myFriendId;
        this.newFriendId_ = newFriendId;
        this.addContactId_ = addContact;
        console.log(this.siteOwnerId_);
        $(this.myFriendId_).on('click',{that:this},this.queryFriends);
        console.log(this.addContactId_);
        console.log($(this.addContactId_));
        $(this.addContactId_).on('click',{that:this},this.openFindFriend);
        $(this.newFriendId_).on('click',{that:this},this.queryNewFriends);
    }
    getSiteOwnerTemplate() {
        return `                                            
            <li class="user">
                <div class="user-title">
                <img src="" alt="头像" class="user-header">
                </div>
                <div class="user-info">
                    <h5>我的站主<h5>
                    <p><small>个性签名</small></p>
                </div>
            </li>`;
    }
    getFriendsTemplate() {
        return `{{#friends}}                                        
            <li class="user">
                <img src="{{friendHeader}}" alt="头像" class="radius-friend-header">
                <div class="user-info">
                    <h6>{{friendName}}</h6>
                    <p><span><small>{{friendSign}}</small></span></p>
                </div>
            </li>
            {{/friends}}`;
    }
    getNewFriendTemplate() {
        return `
            <li class="new-friend">
               {{{sub}}}
            </li>
        `;
    }
    queryFriends(event) {
        console.log("happen!");
        let that = event.data.that;
        let http = new XMLHttpRequest();
        http.open('GET',that.getFriendURL(that.userId_),true);
        http.send();
        http.onreadystatechange = function() {
            if (http.readyState === 4 && http.status === 200) {
                let ul = $(that.myFriendId_).siblings('ul').get(0);
                let span = $(that.myFriendId_).children('span');
                if (span.text() == '+') {
                    span.text('-');
                } else {
                    span.text('+');
                }
                that.readFriends(http,ul);
            }
        };
    }
    readFriends(http,ul) {
        let data = JSON.parse(http.responseText);
        if (data.success) {
            let friends = this.parseFriends(data.data);
            let execStr = mustache.render(this.getFriendsTemplate(),{friends:friends});
            $(ul).html(execStr);
            $(ul).toggle();
        }
    }
    parseFriends(data) {
        let friends = new Array();
        for (let i in data) {
            let temp = data[i];
            let friend = new Object();
            friend.friendId = temp.id;
            friend.friendName = temp.name;
            friend.friendSign = temp.sign;
            friend.friendHeader = this.getHeaderURL(temp.id);
            friends.push(friend);
        }
        return friends;
    }
    openFindFriend(event) {
        let panelId = '#findFriend';
        console.log($(panelId));
        window.panel.resetCurrentPanel(panelId);
        let friends = new Friend('#findFriend','#friendsSet','#findFriendInput','#closeFindFriend');
    }

    queryNewFriends(event) {
        let that = event.data.that;
        let http = new XMLHttpRequest();
        let url = that.getFriendActionURL(that.userId_);
        http.open('GET',url,true);
        http.send();
        http.onreadystatechange = function() {
            if (http.readyState === 4 && http.status === 200) {
                that.afterNewFriend(http);
            }
        };
    }
    afterNewFriend(http) {
        console.log("请求结束");
        let data = JSON.parse(http.responseText);
        console.log(data);
        this.parseNewFriends(data.data);
    }
    parseNewFriends(data) {
        let records = new Array();
        let ul = $(this.newFriendId_).siblings('ul').get(0);
        $(ul).html("");
        let span = $(this.newFriendId_).children('span').get(0);
        if ($(span).text() == '+') {
            $(span).text('-');
        } else {
            $(span).text('+');
        }
        for (let i in data) {
            let record = new Object();
            let r = data[i];
            if (this.isWait(r.state)) {
                if (r.responseid == this.userId_) {
                    let template = new Object();
                    template.peerId = r.requestid;
                    template.peerName = r.reqname;
                    template.peerEmail = r.reqemail;
                    template.peerHeader = this.getHeaderURL(template.peerId);
                    template.requestMsg = r.requestmsg;
                    record.template = template;
                    record.sub = this.getResponseWaitTemplate();
                    let subRender = mustache.render(this.getNewFriendTemplate(),{sub:record.sub});
                    $(ul).append(mustache.render(subRender,template));
                    console.log("Wait!");
                    let li = $(ul).children('li').last();
                    let button = $($(li).find('.response-confirm').get(0)).children('button').get(0);
                    $(button).on('click',{that:this,peerId:template.peerId},this.confirmToFriend);
                } else {
                    console.log("Request Wait!");
                    let template = new Object();
                    template.peerId = r.responseid;
                    template.peerName = r.resname;
                    template.peerEmail = r.resemail;
                    template.peerHeader = this.getHeaderURL(template.peerId);
                    template.requestState = r.state;
                    record.sub = this.getReuqestWaitTemplate();
                    let subRender = mustache.render(this.getNewFriendTemplate(),{sub:record.sub});
                    $(ul).append(mustache.render(subRender,template));
                }
            } else if (this.isConfirmed(r.state)) {
                if (r.requestid == this.userId_) {
                    let template = new Object();
                    template.peerId = r.responseid;
                    template.peerName = r.resname;
                    template.peerEmail = r.resemail;
                    template.peerHeader = this.getHeaderURL(template.peerId);
                    template.requestState = r.state;
                    record.sub = this.getRequestConfirmedTemplate();
                    let subRender = mustache.render(this.getNewFriendTemplate(),{sub:record.sub});
                    $(ul).append(mustache.render(subRender,template));
                }
            } else {

            }
        }
        console.log(records);
        $(ul).toggle();
    }
    getResponseWaitTemplate() {
        return `   
                <div class="response-wait">
            <div class="response-header">
                <img class="radius-friend-header" src="{{peerHeader}}">
            </div>
            <div class="response-info">
                <div class="response-info-title">
                    {{peerName}}
                    <span><small>（{{peerEmail}})</small></span>
                </div>
                <span>{{requestMsg}}</span>
            </div>
            <div class="response-confirm">
                <button class="btn btn-primary">同意</button>
            </div>
        </div>
        `;
    }
    getReuqestWaitTemplate() {
        return `
            <div class="request-wait">
                <div class="request-header">
                    <img src="{{peerHeader}}" class="radius-friend-header">
                </div>
                <div class="request-info">
                    <div class="request-info-title">    
                        <span>{{peerName}}</span>
                        <span><small>{{peerEmail}}</small></span>
                    </div>
                </div>
                <div class="request-state">
                    <span>{{requestState}}</span>
                </div>
            </div>
        `;
    }
    getRequestConfirmedTemplate() {
        return `
            <div class="request-confirm">
                <div class="request-header">
                    <img class="radius-friend-header" src="{{peerHeader}}}">
                </div>
                <div class="request-info">
                    <div class="request-info-title">
                        <span>{{peerName}}</span>
                        <span><small>{{peerEmail}}</small></span>
                    </div>
                </div>
                <div class="request-state">
                    <span>{{requestState}}</span>
                </div>
            </div>
        `;
    }
    confirmToFriend(event) {
        let that = event.data.that;
        let peerId = event.data.peerId;
        let url = that.getFriendActionURL(that.userId_);
        let http = new XMLHttpRequest();
        let data = {
            peerId:peerId
        };
        http.open('POST',url,true);
        http.send(JSON.stringify({data:data}));
        http.onreadystatechange = function() {
            if(http.readyState === 4 && http.status === 200) {
                that.afterConfirmFriend(http);
            }
        };
    }
    afterConfirmFriend(http) {
        console.log("After Confirm");
    }
    isWait(state) {
        const WAIT = "等待同意";
        if (state == WAIT) {
            return true;
        }
        return false;
    }
    isRefused(state) {
        const REFUSED = "已拒绝";
        if (state == REFUSED) {
            return true;
        }
        return false;
    }
    isConfirmed(state) {
        const CONFIRMED = "已同意";
        if (state == CONFIRMED) {
            return true;
        }
        return false;
    }
}

export class Friend extends Config{
    constructor(findFriendId,friendsSet,findFriendInput,closeFindFriend) {
        super();
        this.userId_ = Number(sessionStorage.getItem('id'));
        this.findFriendId_ = findFriendId;
        this.friendsSetId_ = friendsSet;
        this.findFriendInputId_ = findFriendInput;
        this.closeFindFriend_ = closeFindFriend;
        console.log(this.findFriendInputId_);
        console.log($(this.findFriendInputId_));
        $(this.findFriendInputId_).on('input',{that:this},this.find);
        $(this.closeFindFriend_).on('click',function() {
            window.panel.resetCurrentPanel(null);
        });
    }
    getFindFriendTemplate() {
        return `{{#friends}}
            <li class="find-friend-individal">
                <div class="friend-header">
                    <img class="radius-friend-header" src="{{friendHeader}}" alt="头像">
                </div>
                <div class="friend-info">
                    <h6>{{friendName}}</h6>
                    <p><span><small>{{friendEmail}}</small></span></p>
                </div>
                <div class="friend-add">
                    <button class="btn btn-info">加为好友</button>
                </div>
            </li>{{/friends}}
        `;
    }
    find(event) {
        let that = event.data.that;
        console.log(this.value);
        let regex = new RegExp("[a-zA-Z.@0-9_-]+");
        if (!regex.test(this.value)) {
            console.log(this.value);
            $(that.friendsSetId_).html("");
            return;
        }
        let http = new XMLHttpRequest();
        let query = this.value;
        let url = that.getFindFriendURL(that.userId_) + "?query=" + query;
        http.open('GET',url,true);
        http.send();
        http.onreadystatechange = function() {
            if (http.readyState === 4 && http.status === 200) {
                that.parseFriendsSet(http);
            }
        };
    }
    parseFriendsSet(http) {
        let data = JSON.parse(http.responseText);
        if (data.success) {
            let friends = this.parseFriends(data.data);
            $(this.friendsSetId_).html(mustache.render(this.getFindFriendTemplate(),{friends:friends}));
            let lis = $(this.friendsSetId_).children('li');
            for (let i = 0; i < lis.length; ++i) {
                let info = $(lis[i]).find('.friend-info');
                if (info.length < 1) {
                    continue;
                }
                let img = $(lis[i]).find('img').get(0);
                let friendHeader = $(img).attr('src');
                let friendName = $($(info.get(0)).find('h6').get(0)).text();
                let friendEmail = $($(info.get(0)).find('small').get(0)).text();
                let addButton = $(lis[i]).find('button').get(0);
                let addInfo = new Object();
                addInfo.friendName = friendName;
                addInfo.friendEmail = friendEmail;
                addInfo.friendHeader = friendHeader;
                $(addButton).on('click',{info:addInfo,that:this},this.addFriend);
            }
        }
    }
    addFriend(event) {
        let info = event.data.info;
        let that = event.data.that;
        let addFriendInfoId = '#addFriendInfo';
        $(addFriendInfoId).html(mustache.render(that.getAddFriendTemplate(),{info:info}));
        let addFriendId = '#addFriend';
        window.panel.resetCurrentPanel(addFriendId);
        let aimAddFriend = '#aimAddFriend';
        let aimCloseAdd = '#aimCloseAdd';
        $(aimAddFriend).on('click',{that:that,email:info.friendEmail},that.aimAddFriend);
        $(aimCloseAdd).on('click',that.aimCloseAdd);
    }

    aimAddFriend(event) {
        event.preventDefault();
        let that = event.data.that;
        let email = event.data.email;
        let msg = document.addFriendForm.message.value;
        let defaultMsg = document.addFriendForm.message.placeholder;
        let sendMsg = defaultMsg;
        let regex = new RegExp("^\s*$");
        if (!regex.test(msg)) {
            sendMsg = msg;
        }
        let http = new XMLHttpRequest();
        console.log(that);
        let url = that.getAddFriendURL(that.userId_) + '?msg=' + sendMsg + '&email=' + email;
        http.open('GET',url,true);
        http.send();
        http.onreadystatechange = function() {
            if (http.readyState === 4 && http.status === 200) {
                that.afterAddFriend(http);
            }
        };
    }
    afterAddFriend(http) {
        let data = JSON.parse(http.responseText);
        console.log("添加后！");
        if (data.success) {
            //to do
        }
        window.panel.resetCurrentPanel();
    }
    aimCloseAdd(event) {
        event.preventDefault();
        window.panel.resetCurrentPanel(null);
    }

    getAddFriendTemplate() {
        return `{{#info}}
            <div class="add-info">
        <div class="add-info-header">
            <img class="radius-friend-header" src="{{friendHeader}}" alt="头像">
        </div>
        <div class="add-info-hint">
            <h6>{{friendName}}</h6>
            <p><span><small>{{friendEmail}}</small></span></p>
        </div>
    </div>
    <form name="addFriendForm">
        <div class="form-group">
            <label for="message">附加消息</label>
            <input type="text" name="message" class="form-control" placeholder="您好,我是{{friendName}}。">
        </div>
        <div class="form-group add-submit">
            <button type="button" id="aimAddFriend" class="btn btn-success">加为好友</button>
            <button type="button" id="aimCloseAdd" class="btn btn-danger">关闭</button>
        </div>
    </form>
    {{/info}}`;
    }
    parseFriends(data) {
        let friends = new Array();
        let ownerEmail = sessionStorage.getItem('email');
        for (let i in data) {
            let temp = data[i];
            if (ownerEmail == temp.email) {
                continue;
            }
            let friend = new Object();
            friend.friendName = temp.name;
            friend.friendHeader = this.getHeaderURL(temp.id);
            friend.friendEmail = temp.email;
            friends.push(friend);
        }
        return friends;
    }
}
$(document).ready(function () {
    let contacts = new Contacts('#siteOwner','#myFriend','#newFriend','#addContact');
    //
});
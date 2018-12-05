import $ from 'jquery';
import mustache from 'mustache';
import monment from 'moment';
import { isArray, isNull } from 'util';
import { isUndefined } from '_util@0.10.4@util';
//fixed me by modified the {let of} || {let in} to foreach
/*
    Template of contact    
    <li class="list-group-item">
        <div class="row message-contact">
            <div class="col col-1">
                <img class="user-header" src="{{contactHeader}}" alt="">
            </div>
            <div class="col message-contact-info">
                <p><span>{{contactName}}</span><small>{{contactTime}}</small></p>
                <p><small>{{contactMsg}}</small><span class="badge badge-danger">{{unRead}}</span></p>
            </div>
        </div>
    </li>
 */
/*
    <li class="list-group-item message-by-{{msgOwner}}">
        <div class="message-owner">    
            <img src={{msgUserHeader}} alt="头像" class="user-header">
            <p><h5><span>{{msgActive}}<span><span>{{msgUserName}}<span></h5></p>
        </div>
        <div class="message-content">
            <span>{{msgContent}}</span>
        </div>
    </li>
*/

export default class Chat {
    constructor(messageInput,sendButton,messageRecord,messageSession,chatSession,closeSession,sessionHeader,chatModalBody,selfCenter) {
        let that = this;
        this.messageInput_ = messageInput;
        this.sendButton_ = sendButton;
        this.messageRecord_ = messageRecord;
        this.messageSession_ = messageSession;
        this.chatSession_ = chatSession;
        this.closeSession_ = closeSession;
        this.sessionHeader_ = sessionHeader;
        this.userId_ = Number(sessionStorage.getItem('id'));
        this.currentSessionId_ = null;
        this.currentSessionInfo_ = null;
        this.currentUserInfo_ = null;
        this.chatModalBody_ = chatModalBody;
        this.selfCenter_ = selfCenter;
        console.log(sessionStorage.getItem('id'));
        this.initRecordPanel().then(function(data){that.initWorker(that);});
        console.log(that.sessions_);
        $(this.messageRecord_).on('click','li',function(event) {
            let sessionInfo = JSON.parse($(this).attr("value"));
            let sessionId = sessionInfo.sessionId;
            let records_ = that.records_;
            let header = new Object();
            that.closeUserInfo();
            that.currentSessionId_ = sessionId;
            that.currentSessionInfo_ = sessionInfo;
            for (let i in records_) {
                let obj = records_[i];
                if (obj.sessionId == sessionId) {
                    header.peerName = obj.peerName;
                    header.peerId = obj.peerId;
                }
            }
            let userId = sessionStorage.getItem('id');
            $(that.sessionHeader_).html(mustache.render(that.getSingleSessionHeaderTemplate(),header));
            that.displayMessages(sessionId,userId,that);
            $(chatSession).show();
            $(that.closeSession_).on('click',function(){
                console.log("close");
                $(that.chatSession_).hide();
                that.currentSessionId_ = null;
                that.currentSessionInfo_ = null;
            });
        });
        $(this.sendButton_).on('click',{that:this},this.sendMessage);
        $(this.selfCenter_).on('click',{that:this},this.displayUserInfo);
    }
    sendMessage(event) {
        let that = event.data.that;
        let content = $(that.messageInput_).val();
        let message = new Object();
        let sessionInfo = that.currentSessionInfo_;
        message.sessionId = sessionInfo.sessionId;
        message.firstId = sessionInfo.userId;
        message.secondId = sessionInfo.peerId;
        let date = monment().format("YYYY-MM-D HH:mm:ss");
        message.createTime = date;
        message.content = content;
        message.ownerId = sessionInfo.userId;
        console.log(message);
        let request = {
            request:"message",
            data:message
        };
        that.worker_.postMessage(JSON.stringify(request));
        console.log("发送数据!");
        $(that.messageInput_).val("");
        console.log($(that.chatModalBody_));
        console.log($(that.chatModalBody_)[0].scrollHeight);
        
    }
    
    getRecordTemplate() {
        return `{{#records}}<li value="{{sessionInfo}}" class="list-group-item">
        <div class="row message-contact">
            <div class="col col-2">
                <img class="msguser-header" src="http://localhost:12000/userheader/{{peerId}}" alt="">
            </div>
            <div class="col message-contact-info">
                <p><span>{{peerName}}</span><small class="dateHint">{{createTime}}</small></p>
                <p><small class="contentHint">{{content}}</small><span class="badge badge-danger">0</span></p>
            </div>
        </div>
    </li>{{/records}}`;
    }

    getMessageTemplate() {
        return `{{#messages}}<li class="list-group-item message-by-{{ownerType}}">
        <div class="message-owner">    
            <img src="http://localhost:12000/userheader/{{ownerId}}" alt="头像" class="user-header">
            <!--<p><h5><span>{{msgActive}}<span>--><span>{{ownerName}}<span></h5></p>
        </div>
        <div class="message-content">
            <span>{{content}}</span>
        </div>
    </li>{{/messages}}`;
    }

    initRecordPanel() {
        let http = new XMLHttpRequest();
        let id = sessionStorage.getItem('id');
        console.log("获取数据Id: " + id);
        let url = 'http://localhost:12000/message/record/' + id;
        http.open('GET',url,true);
        http.send();
        let getRealRecord = this.getRealRecord;
        //let messageRecord = this.messageRecord_;
        let getRecordTemplate = this.getRecordTemplate;
        this.sessions_ = new Map();
        this.records_ = new Array();
        let that = this;
        http.onreadystatechange = function() {
            if (http.readyState === 4 && http.status === 200) {
                let func = function() {
                    let data = JSON.parse(http.responseText);
                    let sessions_ = that.sessions_;
                    if (data.success) {
                        console.log(data.data);
                        let records = data.data.records;
                        for (let r in records) {
                            records[r] = getRealRecord(id,records[r]);
                        }
                        that.records_ = records;
                        //console.log(that.records_);
                        //console.log(that.messageRecord_);
                        //console.log($(messageRecord));
                        $(that.messageRecord_).html(mustache.render(getRecordTemplate(),{records:records}));
                        let sessions = data.data.sessions;
                        for (let s in sessions) {
                            let session = sessions[s];
                            for (let m in session) {
                                let message = session[m];
                                session[m] = getRealRecord(id,message);
                            }
                            if (session.length > 0) {
                                sessions_.set(session[0].sessionId,session);
                            }
                        }
                        that.sessions_ = sessions_;
                        console.log("请求完数据!");
                    }
                    return new Promise(function(resolve,reject) {
                        console.log("网络请求完成!");
                        resolve(null);
                    });
                };
                func().then(function(data) {console.log("开始初始化会话!");console.log(that);that.initSessions(that); });
            } 
        }
        return new Promise(function(resolve,reject) {
            console.log("初始化面板!done");
            resolve(null);
        });
    }
    getRealRecord(userId,obj) {
        let ret = new Object();
        if (obj.firstId == userId) {
            ret.peerName = obj.secondName;
            ret.peerId = obj.secondId;
        } else {
            ret.peerName = obj.firstName;
            ret.peerId = obj.firstId;
        }
        let sessionInfo = new Object();
        sessionInfo.userId = Number(userId);
        sessionInfo.peerId = Number(ret.peerId);
        sessionInfo.sessionId = obj.sessionId;
        console.log(sessionInfo);
        obj.sessionInfo = JSON.stringify(sessionInfo);
        return Object.assign(obj,ret);
    }
    getSingleSessionHeaderTemplate() {
        return `<div class="modal-header-title">
        <div class="title-info">
            <img src="http://localhost:12000/userheader/{{peerId}}" alt="头像">    
            <h4>{{peerName}}</h4>
        </div>
        <button id="closeSession" type="button" class="close" data-dismiss="modal">&times;</button>      
    </div>
    <div class="modal-header-info">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link">聊天</a></li>
            <li class="nav-item"><a class="nav-link">设置</a></li>
        </ul>
    </div>`;
    }
    displayMessages(sessionId,userId,that) {
        /*let messages = that.sessions_.get(sessionId);
        let renderMsg = new Array();
        for (let index in messages) {
            let message = messages[index];
            let obj = new Object();
            obj.ownerId = message.ownerId;
            if (message.ownerId == userId) {
                obj.ownerType = "self";
            } else {
                obj.ownerType = "other";
            }
            obj.ownerName = (message.ownerId == message.firstId) ? message.firstName : message.secondName;
            obj.content = message.content;
            renderMsg[index] = obj;
            $(that.messageSession_).html(mustache.render(that.getMessageTemplate(),{messages:renderMsg}));
            
        }*/
        console.log("显示信息!");
        this.realSessions_.get(sessionId).displayMessages();
    }
    parseWorkerInitMessage() {
        let request = "init";
        let data = {
            userId:sessionStorage.getItem('id'),
        };
        this.worker_.postMessage(JSON.stringify({
            request:request,
            data:data
        }));
    }
    responseToInit() {
        this.parseWorkerInitMessage();
    }
    responseToDebug(request) {
        console.log("DEBUG: " + request.data.message);
    }
    isBaseRequest(request) {
        return !(typeof(request.request) === undefined) && !(typeof(request.data) === undefined);
    }
    handleMessage(request,that) {
        let message = request.data;
        console.log("开始处理信息!");
        console.log(message);
        let sessionId = message.sessionId;
        that.realSessions_.get(sessionId).handleMessage(this.currentSessionId_,message);
        console.log(that.realSessions_);
        $(that.chatModalBody_).scrollTop($(that.chatModalBody_)[0].scrollHeight);
    }
    checkRecord(record) {
        return !(typeof(record.sessionId) === undefined) && !(typeof(record.userId) === undefined) && !(typeof(record.peerId) === undefined); 
    }
    initRecords(recordsId) {
       let r = new Map();
       let records = $(recordsId).children();
       for (let index  = 0; index < records.length; ++index) {
           let child = records[index];
           let data = $(child).attr("value");
           if (data === undefined) {
               continue;
           }
           console.log(data);
           let sessionInfo = JSON.parse(data);
           console.log(sessionInfo);
           let tempRecord = new ChatRecord(sessionInfo.sessionId,sessionInfo,records[index]);
           r.set(sessionInfo.sessionId,tempRecord);
       }
       return r;
    }
    initSessions(that) {
        let r = that.initRecords(that.messageRecord_);
        console.log(r);
        that.realSessions_ = new Map();
        console.log("begin init session!");
        r.forEach(function(value,key,map){
            let messages = new MessageQueue(that.sessions_.get(key),that.getMessageTemplate,that.userId_);
            console.log(messages);
            console.log(that.messageSession_);
            let tempSession = new ChatSession(key,value,messages,that.userId_,that.messageSession_);
            //console.log(tempSession);
            console.log("被调用");
            that.realSessions_.set(key,tempSession);
            console.log(that);
        });
        console.log("init session!");
        console.log(that.realSessions_);
    }

    initWorker(that) {
        that.worker_ = new Worker('http://localhost:12000/utils/worker.js');
        that.worker_.onmessage = function(event) {
            console.log(event.data);
            let request = JSON.parse(event.data);
            if (!that.isBaseRequest(request)) {
                console.log("is bad request!");
            }
            switch(request.request) {
                case "init" :
                    that.responseToInit(request);
                    console.log("init!");
                break;
                case "debug" :
                    that.responseToDebug(request);
                break;
                case "message":
                    console.log("返回发送者消息: " + request.data);
                    that.handleMessage(request,that);
                break;
                default:
                    console.log("unknown message!");
                break;
            }
        };
    }
    closeUserInfo() {
        let userInfo = '#userInfo';
        if (!isNull(this.currentUserInfo_)) {
            $(userInfo).hide();
        }
    }
    closeChatSession() {
        $(this.chatSession_).hide();
        this.currentSessionId_ = null;
        this.currentSessionInfo_ = null;
    }
    displayUserInfo(event) {
        let that = event.data.that;
        that.closeChatSession();
        that.currentUserInfo_ = new UserInfo(that.userId_,'#userInfo','#userInfoForm','#changeUserInfo','#uploadHeader','#closeUserInfo');
    }
}
class MessageQueue {
    constructor(messages,getMessageTemplate,userId) {
       console.log("开始构造消息队列!");
       if (!isArray(messages)) {
           console.log("消息队列构造错误!");
           return;
       }
       this.userId_ = userId;
       this.messages_ = new Array();
       for (let m in messages) {
           console.log(m);
           console.log(messages[m]);
            let message = this.formatMessage(messages[m],this.userId_);
            this.messages_.push(message);
       }
       this.unread_ = this.messages_.length;
       this.getMessageTemplate = getMessageTemplate;
    }

    addMessage(message,userId) {
        let validMessage = this.formatMessage(message,userId);
        this.messages_.push(validMessage);
        this.unread_ = this.unread_ + 1;
    }
    formatMessage(message,userId) {
        console.log("format message!");
        console.log(message);
        let ownerId = message.ownerId;
        if (ownerId == message.firstId) {
            message.peerName = message.secondName;
            message.peerId = message.secondId;
            message.ownerName = message.firstName;
        } else {
            message.peerId = message.firstId;
            message.peerName = message.firstName;
            message.ownerName = message.secondName;
        }
        message.userId = userId;
        if (message.ownerId == userId) {
            message.ownerType = "self";
        } else {
            message.ownerType = "other";
        }
        console.log(message);
        return message;
    }
    displayMessages(messageSession) {
        $(messageSession).html(mustache.render(this.getMessageTemplate(),{messages:this.messages_}));
        this.unread_ = 0;
    }
}

class ChatRecord {
    constructor(sessionId,sessionInfo,record) {
        this.sessionId_ = sessionId;
        this.sessionInfo_ = sessionInfo;
        this.record_ = record;
        this.badge_ = this.initBadge();
        console.log(this.badge_);
        this.contentHint_ = this.initContentHint();
        console.log(this.contentHint_);
        this.dateHint_ = this.initDateHint();
    }
    initBadge() {
        return $(this.record_).find('.badge');
    }
    initContentHint() {
        return $(this.record_).find('.contentHint');
    }
    initDateHint() {
        return $(this.record_).find('.dateHint');
    }
    updateDateHint(dateHint) {
        $(this.dateHint_).text(dateHint);
    }
    updateContentHint(content) {
        $(this.contentHint_).text(content);
    }
    updateBadge(count) {
        if (count > 999) {
            count = "999+";
        }
        console.log("update message!");
        $(this.badge_).text(count);
    }
    inMessage(message,count) {
        this.updateBadge(count);
        this.updateContentHint(message.content);
        this.updateDateHint(message.createTime);
    }
}
class ChatSession {
    constructor(sessionId,record,messages,userId,messageSession) {
        console.log("初始化会话!111");
        this.sessionId_ = sessionId;
        this.records_ = record;
        this.messages_ = messages;
        this.userId_ = userId;
        this.messageSession_ = messageSession;
        console.log(this.messages_.unread_);
        this.records_.updateBadge(this.messages_.unread_);
    }
    addMessage(message,userId) {
        this.messages_.addMessage(message,userId);
    }
    displayMessages() {
        this.messages_.displayMessages(this.messageSession_);
        this.records_.updateBadge(0);
    }
    inMessage(message,count) {
        this.records_.inMessage(message,count);
    }
    handleMessage(currentSessionId,message) {
        this.addMessage(message,this.userId_);
        if (currentSessionId == this.sessionId_) {
            this.displayMessages();
            this.messages_.unread_ = 0;
            this.inMessage(message,this.messages_.unread_);
            return;
        }
        console.log("unRead!");
        console.log(this.messages_.unread_);
        this.inMessage(message,this.messages_.unread_);
    }
}

class UserInfo {
    constructor(userId,userInfoId,userInfoFormId,changeUserInfoId,photoId,closeId) {
        this.userId_ = userId;
        this.userInfoId_ = userInfoId;
        this.userInfoFormId_ = userInfoFormId;
        this.routeURL = "http://localhost:12000/userinfo/" + this.userId_;
        this.changeUserInfoId_ = changeUserInfoId;
        this.photoId_ = photoId;
        this.isCorrectPhoto_ = false;
        this.closeId_ = closeId;
        this.getUserInfo();
        //console.log(this.changeUserInfoId_);
        //console.log($(this.changeUserInfoId_));
    }
    getUserInfo() {
        let http = new XMLHttpRequest();
        http.open('GET',this.routeURL,true);
        http.send();
        let that = this;
        let action = function() {
            let data = JSON.parse(http.responseText);
            console.log("获取个人数据!");
            console.log(data);
            if (data.success) {
                data.data.manSex = "";
                data.data.womanSex = "";
                data.data.unknowSex = "";
                if (data.data.sex == "男") {
                    data.data.manSex = "checked";
                } else if (data.data.sex == "女") {
                    data.data.womanSex = "checked";
                } else {
                    data.data.unknowSex = "checked";
                }
                let str = mustache.render(that.getUserInfoTemplate(),data.data);
                $(that.userInfoId_).html(str);
                console.log($(that.closeId_));
                $(that.closeId_).on('click',{that:that},that.closeUserInfo);
            }
            return new Promise(function(resolve,reject) {
                resolve(null);
            });
        };
        http.onreadystatechange = function() {
            if (http.readyState == 4 && http.status == 200) {
                action().then(
                    function (data) {
                        $(that.changeUserInfoId_).on('click',{that:that},that.tryToChangeUserInfo);
                        $(that.photoId_).on('change',{that:that},that.changePhoto);
                        console.log($(that.changeUserInfoId_));
                    }
                );
            }
        };
    }
    tryToChangeUserInfo(event) {
        event.preventDefault();
        let that = event.data.that;
        that.clearErrorHint();
        let name = document.userinfo.name.value;
        let sign = document.userinfo.sign.value;
        let birth = document.userinfo.birth.value;
        let sex = document.userinfo.sex.value;
        let vocation = document.userinfo.vocation.value;
        let company = document.userinfo.company.value;
        let school = document.userinfo.school.value;
        let zone = document.userinfo.zone.value;
        let hometown = document.userinfo.hometown.value;
        let isCorrectPhoto = that.isCorrectPhoto_;
        let photo = isCorrectPhoto ? document.userinfo.photo.files[0]: new Blob();
        let reader = new FileReader();
        let base64Photo;
        console.log(photo);
        reader.readAsDataURL(photo);
        let loadImage = function(event) {
            let result = event.target.result;
            base64Photo = isCorrectPhoto ? result.substring(result.indexOf(',') + 1): "";
            return new Promise(function(resolve,reject) {
                resolve(null);
            });
        };
        reader.onload = function(event) {
            loadImage(event).then(function(d) {
                console.log(name);
                console.log(sign);
                if (birth === "") {
                    birth = "未填写";
                }
                console.log(birth);
                console.log(vocation);
                console.log(company);
                console.log(school);
                console.log(zone);
                console.log(hometown);
                console.log(sex);
                console.log(photo);
                let havePhoto = document.userinfo.photo.files.length >= 1 ? true : false;
                let photoInfo;
                if (havePhoto) {
                    photoInfo = document.userinfo.photo.files[0];
                }
                let photoAbout = havePhoto ? {
                    type: photoInfo.type,
                    size: photoInfo.size
                }: undefined;
                let http = new XMLHttpRequest();
                let data = {
                    name:name,
                    photo:base64Photo,
                    photoAbout:photoAbout,
                    sign:sign,
                    birth:birth,
                    sex:sex,
                    vocation:vocation,
                    company:company,
                    school:school,
                    zone:zone,
                    hometown:hometown
                };
                console.log(data);
                let canSubmit = that.testData(data);
                if (!canSubmit) {
                    return false;
                }
                console.log(canSubmit);
                http.open("POST",that.routeURL,true);
                http.setRequestHeader("Content-Type","application/json");
                http.send(JSON.stringify(data));
                let successFunc = function() {
                    let fetch = JSON.parse(http.responseText);
                    let hint = fetch.hint;
                    let success = fetch.success;
                    if (success) {
                        let update = that.updateSuccess(hint);
                        if (update) {
                            console.log("更新刷新中!");
                            that.getUserInfo();
                        } else {
                            that.showErrorHint("更新信息失败!");
                            return;
                        }
                    } else {
                        let errMsg = that.errorMsg();
                        for (let i in hint) {
                            if (!hint[i]) {
                                that.showErrorHint(errMsg.i);
                                return;
                            }
                        }
                    }
                };
                http.onreadystatechange = function() {
                    if (http.readyState === 4 && http.status === 200) {
                        successFunc();
                    }
                };
            });
        };
    }
    changePhoto(event) {
        let that = event.data.that;
        that.clearErrorHint();
        let reader = new FileReader();
        let img = this.files[0];
        let regex = new RegExp("(image/jpeg)|(image/jpg)|(image/gif)|(image/png)");
        let type = img.type.trim();
        let errMsg = that.errorMsg();
        if (!regex.test(type.trim())) {
            that.showErrorHint(errMsg.photo);
            that.isCorrectPhoto_ = false;
            return;
        } else {
            that.isCorrectPhoto_ = true;
        }
        let maxSize = 2.0 * 1024 * 1024;
        let size = img.size;
        if (size > maxSize) {
            that.showErrorHint(errMsg.photo);
            that.isCorrectPhoto_ = false;
            return;
        } else {
            that.isCorrectPhoto_ = true;
        }
        reader.readAsDataURL(img);
        reader.onload = function(event) {
            document.getElementById("photoSrc").src = this.result;
        }
    }
    getUserInfoTemplate() {
        return `
        <div class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>个人信息</h4>
                        <button id="closeUserInfo">&times;</button>
                    </div>
                    <div class="modal-body">
                       <div id="infoHint" class="alert-danger">
                            <span></span>
                       </div>
                       <form id="userInfoForm" name="userinfo">
                            <div class="headerInfo">
                                <div class="fuison">
                                    <label for="uploadHeader">
                                        <img id="photoSrc" class="radius-header" src="http://localhost:12000/userheader/82">
                                    </label>
                                    <input name="photo" type="file" accept="image/*" id="uploadHeader">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">帐号/邮箱</label>
                                <input class="form-control" type="email" name="email" value="{{email}}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="sign">个性签名</label>
                                <input class="form-control" name="sign" type="text" value="{{sign}}">
                            </div>
                            <div class="form-group">
                                <label for="name">昵称</label>
                                <input class="form-control" name="name" type="text" value="{{name}}">
                            </div>
                            <div class="form-group">
                                <label>性别</label><br>
                                <!--<input class="form-control" type="text" value="{{sex}}">-->
                                <div id="sexGroup">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="man" value="男" name="sex" {{manSex}}>
                                        <label class="custom-control-label" for="man">男</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="woman" name="sex" value="女" {{womanSex}}>
                                        <label class="custom-control-label" for="woman">女</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="untell" name="sex" value="暂不透露" {{unknowSex}}>
                                        <label class="custom-control-label" for="untell">暂未透露</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birth">生日</label>
                                <input class="form-control" type="date" value="{{birth}}" name="birth" required>
                            </div>
                            <div class="form-group">
                                <label for="vocation">职业</label>
                                <input class="form-control" type="text" name="vocation" value="{{vocation}}">
                            </div>
                            <div class="form-group">
                                <label for="company">公司</label>
                                <input type="text" class="form-control" name="company" value="{{company}}">
                            </div>
                            <div class="form-group">
                                <label for="school">学校</label>
                                <input type="text" class="form-control" name="school" value="{{school}}">
                            </div>
                            <div class="form-group">
                                <label for="zone">地区</label>
                                <input type="text" class="form-control" name="zone" value="{{zone}}">
                            </div>
                            <div class="form-group">
                                <label for="hometown">故乡</label>
                                <input type="text" class="form-control" name="hometown" value="{{hometown}}">
                            </div>
                            <div class="userAction">
                                <button id="changeUserInfo" type="button" class="btn btn-info">修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
    }
    isValidName(name) {
        return name.length <= 30 ? true : false;
    }
    isValidSign(sign) {
        return sign.length <= 80 ? true : false;
    }
    isValidSex(sex) {
        switch (sex) {
            case "男" :
                return true;
            case "女" :
                return true;
            case "暂不透露" :
                return true;
            default :
                return false;
        }
        return false;
    }
    isValidBirth(birth) {
        let regex = new RegExp("([12][0-9]{3})-(([0][1-9])|([1][012]))-([012][0-9])");
        let isDate = regex.test(birth);
        if (isDate) {
            return true;
        }
        if (birth === "未填写") {
            return true;
        }
        return false;
    }
    isValidVocation(vocation) {
        return vocation.length <= 18 ? true : false;
    }
    isValidCompany(company) {
        return company.length <= 40 ? true : false;
    }
    isValidHometown(hometown) {
        return hometown.length <= 30 ? true : false;
    }
    isValidZone(zone) {
        return zone.length <= 50 ? true : false;
    }
    isValidSchool(school) {
        return school.length <= 20 ? true : false;
    }
    testData(data) {
        let errMsg = this.errorMsg();
        if (!isUndefined(data.photoAbout)) {
            let photoAbout = data.photoAbout;
            if (isUndefined(photoAbout.type) || isUndefined(photoAbout.size)) {
                return false;
            }
            let regex = new RegExp("(image/png)|(image/jpg)|(image/jpeg)|(image/gif)");
            if (!regex.test(photoAbout.type)) {
                return false;
            }
            let maxSize = 2.0 * 1024 * 1024;
            if (photoAbout.size > maxSize) {
                return false;
            }
        }
        if (!this.isValidName(data.name)) {
            this.showErrorHint(errMsg.name);
            return false;
        }
        if (!this.isValidSign(data.sign)) {
            this.showErrorHint(data.sign);
            return false;
        }
        if (!this.isValidBirth(data.birth)) {
            this.showErrorHint(data.birth);
            return false;
        }
        if (!this.isValidSex(data.sex)) {
            this.showErrorHint(data.sex);
        }
        if (!this.isValidCompany(data.company)) {
            this.showErrorHint(data.company);
            return false;
        }
        if (!this.isValidHometown(data.hometown)) {
            this.showErrorHint(data.hometown);
            return false;
        }
        if (!this.isValidVocation(data.vocation)) {
            this.showErrorHint(data.vocation);
            return false;
        }
        if (!this.isValidZone(data.zone)) {
            this.showErrorHint(data.zone);
            return false;
        }
        if (!this.isValidSchool(data.school)) {
            this.showErrorHint(data.school);
            return false;
        }
        return true;
    }
    errorMsg() {
        return {
            name:       "昵称填写有误!",
            sign:       "个性签名填写有误!",
            photo:      "头像格式或尺寸有误!",
            sex:        "性别填写有误!",
            birth:      "生日填写有误!",
            vocation:   "职业填写有误!",
            company:    "公司填写有误!",
            zone:       "地区填写有误!",
            hometown:   "家乡填写有误！",
            school:     "学校填写有误!"
        };
    }
    showErrorHint(msg) {
        let id = "#infoHint";
        $(id).find('span').text(msg);
    }
    clearErrorHint() {
        let id = "#infoHint";
        $(id).find('span').text("");
    }
    updateSuccess(hint) {
        for (let i in hint) {
            if (!hint[i]) {
                return false;
            }
        }
        return true;
    }
    closeUserInfo(event) {
        let that = event.data.that;
        console.log("try to close!");
        $(that.userInfoId_).hide();
    }
}

$(document).ready(function() {
    console.log("Websocket Begin");
    let chat = new Chat('#messageInput','#sendButton','#messageRecord','#messageSession','#chatSession','#closeSession','#single-session-header','#chat-modal-body','#selfCenter');
});
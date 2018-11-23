import $ from 'jquery';
import mustache from 'mustache';
import monment from 'moment';
import { isArray } from 'util';
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
    constructor(messageInput,sendButton,messageRecord,messageSession,chatSession,closeSession,sessionHeader) {
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
        console.log(sessionStorage.getItem('id'));
        this.initRecordPanel().then(function(data){that.initWorker(that);});
        console.log(that.sessions_);
        $(this.messageRecord_).on('click','li',function(event) {
            let sessionInfo = JSON.parse($(this).attr("value"));
            let sessionId = sessionInfo.sessionId;
            let records_ = that.records_;
            let header = new Object();
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
    }
    inMessage(message) {
        this.records_.inMessage(message);
    }
    handleMessage(currentSessionId,message) {
        this.addMessage(message,this.userId_);
        if (currentSessionId == this.sessionId_) {
            this.displayMessages();
            this.messages_.unread_ = 0;
            this.inMessage(message,this.messages_.unread_);
            return;
        }
        this.inMessage(message,this.messages_.unread_);
    }
}

$(document).ready(function() {
    console.log("Websocket Begin");
    let chat = new Chat('#messageInput','#sendButton','#messageRecord','#messageSession','#chatSession','#closeSession','#single-session-header');
});
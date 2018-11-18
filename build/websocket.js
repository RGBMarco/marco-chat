import $ from 'jquery';
import mustache from 'mustache';
import monment from 'moment';
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
        this.worker_ = new Worker('http://localhost:12000/utils/worker.js');
        this.worker_.onmessage = function(event) {
            console.log("收到数据!");
            console.log(event.data);
        };
        this.messageInput_ = messageInput;
        this.sendButton_ = sendButton;
        this.messageRecord_ = messageRecord;
        this.messageSession_ = messageSession;
        this.chatSession_ = chatSession;
        this.closeSession_ = closeSession;
        this.sessionHeader_ = sessionHeader;
        this.currentSessionInfo_ = null;
        this.initRecordPanel();
        this.parseWorkerInitMessage();
        let that = this;
        console.log(that.sessions_);
        $(this.messageRecord_).on('click','li',function(event) {
            let sessionInfo = JSON.parse($(this).attr("value"));
            let sessionId = sessionInfo.sessionId;
            let records_ = that.records_;
            let header = new Object();
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
        that.worker_.postMessage(JSON.stringify(message));
        console.log("发送数据!");
    }
    
    getRecordTemplate() {
        return `{{#records}}<li value="{{sessionInfo}}" class="list-group-item">
        <div class="row message-contact">
            <div class="col col-2">
                <img class="msguser-header" src="http://localhost:12000/userheader/{{peerId}}" alt="">
            </div>
            <div class="col message-contact-info">
                <p><span>{{peerName}}</span><small>{{createTime}}</small></p>
                <p><small>{{content}}</small><span class="badge badge-danger">0</span></p>
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
        let url = 'http://localhost:12000/message/record/' + id;
        http.open('GET',url,true);
        http.send();
        let getRealRecord = this.getRealRecord;
        let messageRecord = this.messageRecord_;
        let getRecordTemplate = this.getRecordTemplate;
        this.sessions_ = new Map();
        this.records_ = new Array();
        let that = this;
        http.onreadystatechange = function() {
           if (http.readyState === 4 && http.status === 200) {
               let data = JSON.parse(http.responseText);
               let sessions_ = that.sessions_;
               if (data.success) {
                  console.log(data.data);
                  let records = data.data.records;
                  for (let r in records) {
                      records[r] = getRealRecord(id,records[r]);
                  }
                  that.records_ = records;
                  console.log(that.records_);
                  $(messageRecord).append(mustache.render(getRecordTemplate(),{records:records}));
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
               }
           } 
        }
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
        let messages = that.sessions_.get(sessionId);
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
        }
    }
    parseWorkerInitMessage() {
        let request = "init";
        let data = {
            userId:sessionStorage.getItem('id'),
        };
        this.worker_.postMessage(JSON.stringify({
            reuqest:request,
            data:data
        }));
    }
}

$(document).ready(function() {
    console.log("Websocket Begin");
    let chat = new Chat('#messageInput','#sendButton','#messageRecord','#messageSession','#chatSession','#closeSession','#single-session-header');
});
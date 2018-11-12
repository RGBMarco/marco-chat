import $ from 'jquery';
import mustache from 'mustache';
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
    constructor(messageInput,sendButton,messageTpl,recordTpl,messageRecord,messageSession) {
        //this.woker_ = new Woker('http://localhost:12000/utils/worker.js');
        this.messageInput_ = messageInput;
        this.sendButton_ = sendButton;
        this.messageTplData_ = $(messageTpl).text();
        this.recordTplData_ = $(recordTpl).text();
        console.log($(messageTpl));
        console.log($(recordTpl));
        console.log(this.recordTplData_);
        this.messageRecord_ = messageRecord;
        this.messageSession_ = messageSession;
        let date = new Date();
        let recordJson = {
            contactHeader:"http://localhost:12000/userheader/1",
            contactName:"Marco",
            contactTime:date.toLocaleString(),
            contactMsg:"Hello,The Groly!",
            unRead:"0"
        };
        let messageJsonbyo = {
            msgOwner:'other',
            msgUserHeader:'http://localhost:12000/userheader/1',
            msgActive:1000,
            msgUserName:'Marco',
            msgContent:'Hello,Groly!'
        };

        let messageJsonbym = {
            msgOwner:'other',
            msgUserHeader:'http://localhost:12000/userheader/1',
            msgActive:1000,
            msgUserName:'Marco',
            msgContent:'Hello,Groly!'
        };


        for (let i = 0; i < 20; ++i) {
            $(this.messageRecord_).append(mustache.render(this.getRecordTemplate(),recordJson));
            if (i % 2 == 0) {
            $(this.messageSession_).append(mustache.render(this.getMessageTemplate(),messageJsonbyo));
            }else {
                $(this.messageSession_).append(mustache.render(this.getMessageTemplate(),messageJsonbym));
            }
        }
        console.log(mustache.render(this.recordTplData_,recordJson));
        $(this.sendButton_).on('click',{that:this},this.sendMessage);
        /*this.worker_.onmessage = function(event) {
            
        };*/
    }
    sendMessage(event) {
        let that = event.data.that;
        let data = $(that.messageInput_).val();
        //this.worker_.postMessage(data);
    }
    
    getRecordTemplate() {
        return `<li class="list-group-item">
        <div class="row message-contact">
            <div class="col col-2">
                <img class="msguser-header" src="{{contactHeader}}" alt="">
            </div>
            <div class="col message-contact-info">
                <p><span>{{contactName}}</span><small>{{contactTime}}</small></p>
                <p><small>{{contactMsg}}</small><span class="badge badge-danger">{{unRead}}</span></p>
            </div>
        </div>
    </li>`;
    }

    getMessageTemplate() {
        return `<li class="list-group-item message-by-{{msgOwner}}">
        <div class="message-owner">    
            <img src="{{msgUserHeader}}" alt="头像" class="user-header">
            <p><h5><span>{{msgActive}}<span><span>{{msgUserName}}<span></h5></p>
        </div>
        <div class="message-content">
            <span>{{msgContent}}</span>
        </div>
    </li>`;
    }
}

$(document).ready(function() {
    console.log("Websocket Begin");
    let chat = new Chat('#messageInput','#sendButton','#messageTpl','#recordTpl','#messageRecord','#messageSession');
});
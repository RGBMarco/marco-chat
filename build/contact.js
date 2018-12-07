import $ from 'jquery';
import {Config} from './config';
import mustache from 'mustache';
//import { panel } from './panel';
export default class Contacts extends Config{
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
            <li class="user">
                <img src="" alt="头像" class="user-header">
                <div class="user-info">
                    <h5>新朋友</h5>
                </div>
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
                <div friend-info>
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
        }
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
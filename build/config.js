
export class Config {
    constructor() {
        this.baseURL = "http://localhost:12000";
    }
    getFriendURL(id) {
        return this.baseURL + '/friends/' + id;
    }
    getHeaderURL(id) {
        return this.baseURL + '/userheader/' + id;
    }
    getFindFriendURL(id) {
        return this.baseURL + '/find/friends/' + id;   
    }
    getAddFriendURL(id) {
        return this.baseURL + '/add/friends/' + id;
    }
    getFriendActionURL(id) {
        return this.baseURL + '/action/friends/' + id;
    }
    //获得聊天记录
    getMessageRecordURL(id) {
        return this.baseURL + '/message/record/' + id;
    }
    getWorkerResource() {
        return this.baseURL + '/utils/worker.js';
    }
    getUserInfoURL(id) {
        return this.baseURL + '/userinfo/' + id;
    }
    getBaseURL() {
        return this.baseURL;
    }
}
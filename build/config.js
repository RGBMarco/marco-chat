
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
}
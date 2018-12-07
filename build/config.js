
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
}
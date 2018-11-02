import $ from 'jquery';
export default class Index {
    constructor(signupId,signinId,switchSignin,switchSignup) {
        this.signupId_ = signupId;
        this.signinId_ = signinId;
        this.isSignup_ = false;
        this.switchSignin_ = switchSignin;
        this.switchSignup_ = switchSignup;
        this.width_ = window.innerWidth;
        this.height_ = window.innerHeight;
        $(this.signupId_).css("position","absolute");
        $(this.signupId_).css("top",String(this.height_ / 3) + "px");
        $(this.signinId_).css("position","absolute");
        $(this.signinId_).css("top",String(this.height_ / 3) + "px");
        $(this.switchSignin_).on("click",{that:this},this.switch);
        $(this.switchSignup_).on("click",{that:this},this.switch);
    }
    switch(event) {
        let that = event.data.that;
        $(that.signinId_).toggle();
        $(that.signupId_).toggle();
    }
}
$(document).ready(function() {
   let index = new Index("#register","#signIn","#switchSignin","#switchSignup");
});
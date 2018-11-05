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

export class SignUp {
    constructor(signUpForm,signUpEmail,signUpPw,signUpPwConfirm) {
        this.signUpForm_ = signUpForm;
        this.signUpEmail_ = signUpEmail;
        this.signUpPw_ = signUpPw;
        this.signUpPwConfirm_ = signUpPwConfirm;
        $(this.signUpForm_).on('submit',this.signUp);
        $(this.signUpEmail_).on('blur',this.checkEmail);
        console.log($(this.signUpForm_));
    }
    signUp(event) {
        let email = document.signup.email.value;
        let pw = document.signup.password.value;
        let confirmpw = document.signup.password-confirmation.value;
        return false;
    }
    checkEmail(event) {
        console.log(this.value);
        let email = this.value;
        let regex = new RegExp("^([a-zA-Z0-9]+)@[a-z0-9A-Z]{2,}(\.[a-z]+)$");
        if (regex.test(email)) {
            console.log("正确邮箱帐号!");
        }
        else {
            $(this).css('color','red');
            console.log("不是正确邮箱帐号!");
        }
    }
}

$(document).ready(function() {
   let index = new Index("#register","#signIn","#switchSignin","#switchSignup");
   let sign = new SignUp('#signupForm','#signupEmail','#signupPw','#signupPwConfim');
});
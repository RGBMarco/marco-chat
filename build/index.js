import $ from 'jquery';
import md5 from 'js-md5';
import { isValid } from 'ipaddr.js';
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
    constructor(signUpForm,signUpEmail,signUpPw,signUpPwConfirm,signupHint) {
        this.signUpForm_ = signUpForm;
        this.signUpEmail_ = signUpEmail;
        this.signUpPw_ = signUpPw;
        this.signUpPwConfirm_ = signUpPwConfirm;
        this.signUpHint_ = signupHint;
        this.hintMessage = new Array();
        this.baseURL = "http://localhost:12000";
        $(this.signUpForm_).on('submit',{that:this},this.signUp);
        $(this.signUpEmail_).on('blur',{hintId:this.signUpHint_,msg:this.hintMessage},this.checkEmail);
        $(this.signUpPw_).on('blur',{hintId:this.signUpHint_,msg:this.hintMessage},this.checkPassword);
        $(this.signUpPwConfirm_).on('blur',{hintId:this.signUpHint_,pwId:this.signUpPw_,msg:this.hintMessage},this.confirmPassword);
        console.log($(this.signUpPwConfirm_));
        console.log($(this.signUpForm_));
        console.log($(this.signUpPw_));
    }
    signUp(event) {
        event.preventDefault();
        let email = document.signup.email.value;
        let pw = document.signup.password.value;
        let confirmpw = document.signup.passwordconfirmation.value;
        let that = event.data.that;
        let canSubmit = that.isValidEmail(email) && that.isValidPassword(pw) && that.isValidConfirm(pw,confirmpw);
        if (canSubmit) {
            let request = new XMLHttpRequest();
            let url = that.baseURL + '/user';
            let data = {
                email:email,
                password:md5(pw)
            };
            request.open("POST",url,true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
            request.send(JSON.stringify(data));
            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                   let data = JSON.parse(request.responseText);
                   console.log(data);
                   console.log(Boolean(data.success));
                   if (String(data.success) === String("false")) {
                        let m = {
                            id:'tonet',
                            msg:'帐号已存在',
                        }
                        $(that.signUpHint_).find('span').text(m.msg);
                        $(that.signUpHint_).show();
                        console.log(m.msg);
                        console.log($(that.signupHint).show());
                        $(that.signUpHint_).show();
                        console.log($(that.signUpHint_).find('span'));
                        return;
                   }
                   window.location.href= data.url;
                } else if (request.readyState === 4 && request.status === 400) {
                    $(that.hintId).find('span').text('恶意请求');
                    $(that.hintId).show();
                }
            };
        } else {
            console.log("用户不能提交!");
        }
        return canSubmit;
    }
    checkEmail(event) {
        console.log(this.value);
        let hintId = event.data.hintId;
        let msgs = event.data.msg;
        let email = this.value;
        let regex = new RegExp("^([a-zA-Z0-9]+)@[a-z0-9A-Z]{2,}(\.[a-z]+)$");
        let m = {
            id:'email',
            msg:'请输入正确的邮箱!'
        };
        if (regex.test(email)) {
            for (let index in msgs) {
                let t = msgs[index].id;
                if (m.id === t) {
                    msgs.splice(index,1);
                    break;
                }
            }
            if (msgs.length === 0) {
                $(hintId).hide();
            } else {
                console.log(msgs.length);
                $(hintId).find('span').text(msgs[msgs.length - 1].msg);
            }
            return true;
        } else {
            for (let index in msgs) {
                let t = msgs[index].id;
                if (m.id === t) {
                    msgs.splice(index,1);
                    break;
                }
            }
            msgs.push(m);
            $(hintId).find('span').text(m.msg);
            $(hintId).show();
            return false;
        }
    }
    checkPassword(event) {
        let hintId = event.data.hintId;
        let msgs = event.data.msg;
        let pw = this.value;
        let regex = new RegExp("[0-9a-zA-Z]{6,15}");
        let m = {
            id:'password',
            msg:'密码只能是长度为6-15为的数字,字母混合字符串'
        };
        if (regex.test(pw)) {
            for (let index in msgs) {
                let t = msgs[index].id;
                if (t === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            if (msgs.length === 0) {
                $(hintId).hide();
            } else {
                $(hintId).find('span').text(msgs[msgs.length - 1].msg);
            }
            return true;
        } else {
            for (let index in msgs) {
                let t = msgs[index].id;
                if (t === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            msgs.push(m);
            $(hintId).find('span').text(m.msg);
            $(hintId).show();
            return false;
        }
    }
    confirmPassword(event) {
        let hintId = event.data.hintId;
        let msgs = event.data.msg;
        let confirmpw = this.value;
        let pwId = event.data.pwId;
        let pw = $(pwId).val();
        console.log(pw);
        let m = {
            id:'confirm',
            msg:'请确保两次密码输入一致'
        };
        if (pw === confirmpw) {
            for (let index in msgs) {
                let t = msgs[index].id;
                if (t === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            if (msgs.length === 0) {
                $(hintId).hide();
            } else {
                $(hintId).find('span').text(msgs[msgs.length - 1].msg);
            }
            return true;
        } else {
            for (let index in msgs) {
                let t = msgs[index].id;
                if (t === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            msgs.push(m);
            $(hintId).find('span').text(m.msg);
            $(hintId).show();
            return false;
        }
    }
    isValidEmail(email) {
        let regex = new RegExp("^([a-zA-Z0-9]+)@[a-z0-9A-Z]{2,}(\.[a-z]+)$");
        return regex.test(email);
    }
    isValidPassword(pw) {
        let regex = new RegExp("[0-9a-zA-Z]{6,15}");
        return regex.test(pw);
    }
    isValidConfirm(pw,confirmpw) {
        return pw === confirmpw;
    }
}
export class SignIn {
    constructor(signinForm,signinEmail,signinPw,signinHint) {
        this.signinForm_ = signinForm;
        this.signinEmail_ = signinEmail;
        this.signinPw_ = signinPw;
        this.signinHint_ = signinHint;
        this.hintMsgs_ = new Array();
        this.baseURL = "http://localhost:12000";
        $(this.signinForm_).on('submit',{that:this},this.login);
        $(this.signinEmail_).on('blur',{that:this},this.checkEmail);
        $(this.signinPw_).on('blur',{that:this},this.checkPassword);
    }
    login(event) {
        event.preventDefault();
        let that = event.data.that;
        let email = document.signin.email.value;
        let pw = document.signin.password.value;
        let remember = document.signin.remember.checked;
        console.log(email);
        console.log(pw);
        console.log(remember);
        let canSubmit = that.isValidEmail(email) && that.isValidPassword(pw);
        if (canSubmit) {
            let http = new XMLHttpRequest();
            let url = that.baseURL + "/user?e=" + email + "&p=" + md5(pw) + "&r=" + remember;
            http.open("GET",url,true);
            console.log(url);
            http.send();
            http.onreadystatechange = function() {
                if (http.readyState === 4 && http.status === 200) {
                    let data = JSON.parse(http.responseText);
                    console.log(data);
                    if (data.success) {
                        window.location.href = data.url;
                    } else {
                        let m = {
                            id:"login",
                            msg:"密码错误"
                        };
                        $(that.signinHint_).find('span').text(m.msg);
                        $(that.signinHint_).show();
                    }
                }
            };
            return true;
        }
        return false;
    }
    isValidEmail(email) {
        let regex = new RegExp("^([a-zA-Z0-9]+)@[a-z0-9A-Z]{2,}(\.[a-z]+)$");
        return regex.test(email);
    }
    isValidPassword(pw) {
        let regex = new RegExp("[0-9a-zA-Z]{6,15}");
        return regex.test(pw);
    }
    checkEmail(event) {
        let that = event.data.that;
        let email = $(that.signinEmail_).val();
        let msgs = that.hintMsgs_;
        let m = {
            id:'email',
            msg:"您的邮箱输入不正确!"
        };
        if (that.isValidEmail(email)) {
            for (let index in msgs) {
                if (msgs[index].id === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            if (msgs.length === 0) {
                $(that.signinHint_).hide();
            } else {
                $(that.signinHint_).find('span').text();
            }
        } else {
            for (let index in msgs) {
                if (msgs[index].id === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            msgs.push(m);
            $(that.signinHint_).find('span').text(m.msg);
            $(that.signinHint_).show();
        }
    }
    checkPassword(event) {
        let that = event.data.that;
        let msgs = that.hintMsgs_;
        let pw = $(that.signinPw_).val();
        let m = {
            id:'password',
            msg:"您的密码输入有误!"
        };
        if (that.isValidPassword(pw)) {
            for (let index in msgs) {
                if (msgs[index].id === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            if (msgs.length === 0) {
                $(that.signinHint_).hide();
            } else {
                $(that.signinHint_).find('span').text();
            }
        } else {
            for (let index in msgs) {
                if (msgs[index].id === m.id) {
                    msgs.splice(index,1);
                    break;
                }
            }
            msgs.push(m);
            $(that.signinHint_).find('span').text(m.msg);
            $(that.signinHint_).show();
        }
    }
}
$(document).ready(function() {
   let index = new Index("#register","#signIn","#switchSignin","#switchSignup");
   let signUp = new SignUp('#signupForm','#signupEmail','#signupPw','#signupPwConfirm','#signupHint');
   let signIn = new SignIn("#signinForm","#signinEmail","#signinPw","#signinHint");
});
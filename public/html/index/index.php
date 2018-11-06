<!DOCTYPE html>
<html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
        <title>主页</title>
        <script type="text/javascript" src="http://localhost:12000/utils/app.js"></script>
        <script type="text/javascript" src="http://localhost:12000/utils/index.js"></script>
        <link rel="stylesheet" href="http://localhost:12000/utils/index.css?version=1.2">
        <style>
            .bg {
                width:100%;
                height:100%;
                position:fixed;
            }
        </style>
    </head>
    <body>
        <img class="bg" src="http://localhost:14000/bg.jpg">
        <main>
            <div id="signIn" class="col col-4 offset-6">
                <div class="card">
                    <div class="card-header">
                        <h4>登录</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="邮箱">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="密码">
                            </div>
                            <div class="form-group utils">
                                <label><input type="checkbox">记住密码</label>
                                <a target="_blank">?忘记密码</a>
                            </div>
                            <div class="center-button">
                                <button class="btn btn-info">登录</button>    
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <span>?没有帐号 <a id="switchSignup" href="#">注册</a></span>
                    </div>
                </div>
            </div>
            <div id="register" class="col col-4 offset-6">
                <div class="card">
                    <div class="card-header">
                        <h4>注册</h4>
                    </div>
                    <div class="card-body">
                        <form id="signupForm" action="" name="signup">
                            <div id="signupHint"class="alert alert-danger">
                                <span></span>
                            </div>
                            <div class="form-group">
                                <input id="signupEmail" type="email" class="form-control" name="email" placeholder="请输入邮箱">    
                            </div>
                            <div class="form-group">
                                <input id="signupPw" type="password" class="form-control" name="password" placeholder="请输入密码">
                            </div>
                            <div class="form-group">
                                <input id="signupPwConfirm" type="password" class="form-control" name="passwordconfirmation" placeholder="请确认密码">
                            </div>
                            <div class="center-button">
                                <button type="submit" class="btn btn-info">注册</button>    
                            </div>  
                        </form>    
                    </div>
                    <div class="card-footer">
                        <span>已有帐号！ <a id="switchSignin" href="#">登录</a><span>    
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
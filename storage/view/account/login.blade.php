@extends('layout-app')

@section('title', '登录')

@section('content')
    <div class="row">
        <div class="col-sm-4" style="height: 20vh;">&nbsp;</div>
    </div>
    <form id="loginForm" class="form-horizontal" action="/login" method="post">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">账号</span>
                    <input type="text" name="username" class="form-control" placeholder="请输入账号" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">密码</span>
                    <input type="password" name="password" class="form-control" placeholder="请输入密码" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="col-sm-4"></div>
        </div>

        <div class="form-group">
            <p class="col-sm-2">&nbsp;</p>
        </div>
        <div class="form-group">
            <p class="col-sm-3">&nbsp;</p>
            <label for="toupiao-fen" class="col-sm-2 control-label">&nbsp;</label>
            <div class="col-sm-1">
                <button id="loginSubmit" class="btn btn-primary form-control" type="submit" name="submit" data-in-processing="提交中" data-ready="提交" data-state="0">提交</button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#loginSubmit").click(function () {
                const thisStateCS = new StateCS($(this));

                if (thisStateCS.isInProcessing()) {
                    return false;
                }

                thisStateCS.inProcessing();

                setTimeout(function () {
                    thisStateCS.prepareReady();
                }, 3000);

                let data = {};

                $.each($("#loginForm").serializeArray(), function () {
                    data[this.name] = this.value;
                });

                data = setUnixTimestamp(data);

                let rsa = new RSA();
                let rsaP = rsa.passwordEncrypt(data.password);

                console.log("rsa password: " + rsaP);

                console.log("sha256: " + CryptoJS.SHA256(data.password));

                return false;

                data.password = passwordEncrypt(data.password, secretKey(), getUnixTimestamp(data));

                console.log(formatQuery(data, secretKey()));

                return false;
            });
        });
    </script>
@endsection

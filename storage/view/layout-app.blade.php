<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="aabbbcc">
        <meta name="secret-key" content="isjdf3LL3o90jFHaplfhf">
        <title>@yield('title') - {{ config('app_name') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/crypto-js@4.0.0/crypto-js.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jsencrypt@3.1.0/bin/jsencrypt.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style type="text/css">
            .navbar-white.navbar .icon-bar {background-color: #7FD6FB85;}
            .title {color: #181818;}
            .pub-content {min-height: 85vh;overflow:scroll;}
            footer.pub-footer {
                min-height: 50px;
                background-color: #7FD6FB85;
            }
        </style>
        <script type="text/javascript">

// 仅限普通压缩
const apiUrl = {
    base: "{{ config('appBaseURL') }}",
    passwordPublic() {
        return this.base + "/key/password/public";
    },
    toString() {
        return this.base;
    }
};

// 混淆压缩
class StateCS {
    constructor(obj) {
        this.obj = obj;

        this.state = {
            inProcessing: 1,
            prepareReady: 0
        };
    }

    setState(s) {
        this.obj.data("state", s);
    }

    getState() {
        return this.obj.data("state");
    }

    setStateInProcessing() {
        this.setState(this.state.inProcessing);
        this.obj.removeClass("btn-primary");
        this.obj.attr("disabled", true);
        this.obj.attr("aria-disabled", true);
    }

    setStatePrepareReady() {
        this.setState(this.state.prepareReady);
        this.obj.addClass("btn-primary");
        this.obj.attr("disabled", false);
        this.obj.attr("aria-disabled", false);
    }

    inProcessing() {
        this.setStateInProcessing();
        this.obj.text(this.obj.data("in-processing"));
    }

    prepareReady() {
        this.setStatePrepareReady();
        this.obj.text(this.obj.data("ready"));
    }

    isInProcessing() {
        if (this.getState().toString() === this.state.inProcessing.toString()) {
            return true;
        }

        return false;
    }

    isPrepareReady() {
        if (this.getState().toString() === this.state.prepareReady.toString()) {
            return true;
        }

        return false;
    }
}
class RSA {
    constructor(props) {
      this.state = {};

      this.props = props;
    }
    encrypt(data, key) {
        let crypt = new JSEncrypt();

        crypt.setPublicKey(key);

        return crypt.encrypt(data);
    }
    passwordEncrypt(data) {
        return this.encrypt(data, this.getPasswordPublicKey());
    }

    getPasswordPublicKey() {
        return $.ajax({
            url: apiUrl.passwordPublic(),
            async: false
        }).responseText;
    }
}
function randomString(e = 0, length = 32) {
    e = parseInt(e);
    length = length - e;
    let str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let returnStr = "";
    for (let i = length; i >= 0; i--) {
        returnStr += str.charAt(Math.floor(Math.random() * str.length));
    }
    return returnStr;
}
function unixTimestamp() {
    return Math.round(new Date().getTime() / 1000);
}
function setUnixTimestamp(data) {
    data.timestamp = unixTimestamp();
    return data;
}
function getUnixTimestamp(data) {
    if (data.hasOwnProperty("timestamp")) {
        return data.timestamp;
    }

    return unixTimestamp();
}
function nonce() {
    let firstHalf = new Date().getTime().toString();
    return firstHalf + randomString(firstHalf.length, 64);
}
function setNonce(data) {
    data.nonce = nonce();
    return data;
}
function getNonce(data) {
    if (data.hasOwnProperty("nonce")) {
        return data.nonce;
    }

    return nonce();
}
function typeIs(d, type) {
    if (typeof(d) !== type) {
        console.log("type d false, d: ");
        console.log(d);
        console.log("typeof: " + typeof(d));
        return false;
    }

    return true;
}
function typeIsObject(object) {
    if (! typeIs(object, "object")) {
        return false;
    }

    return true;
}
function csrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}
function secretKey() {
    return $('meta[name="secret-key"]').attr('content');
}
function passwordEncrypt(password, key, randomString = "") {
    password = CryptoJS.SHA3(password).toString();

    console.log("password: " + password);

    return CryptoJS.HmacSHA3(password + randomString, key).toString();
}
function objectSort(object) {
    if (! typeIsObject(object)) {
        return false;
    }

    let keys = Object.keys(object).sort();
    let returnObject = {};

    for (let i = 0; i < keys.length; ++i) {
        returnObject[keys[i]] = object[keys[i]];
    }

    return returnObject;
}
function object2Query(object) {
    if (! typeIsObject(object)) {
        return false;
    }

    let returnData = [];

    for (let key in object) {
        returnData.push(encodeURIComponent(key) + "=" + encodeURIComponent(object[key]));
    }

    return returnData.join("&");
}
function formatQuery(data, key) {
    if (! typeIsObject(data)) {
        return false;
    }

    if (! data.hasOwnProperty("timestamp")) {
        data = setUnixTimestamp(data);
    }
    if (! data.hasOwnProperty("nonce")) {
        data = setNonce(data);
    }

    data = objectSort(data);

    data.secretKey = key;
    let query = object2Query(data);
    data.sign = CryptoJS.SHA3(query).toString();

    delete data.secretKey;

    return data;
}


        </script>
    </head>
    <body>
        <header class="pub-header">
            <nav class="navbar-white navbar">
              <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="/"><strong class="title">巨灵信用</strong></a>
                </div>

                @if (empty($accountInfo) === false)
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li class="active"><a href="/records">历史</a></li>
                    <li><a href="/ranking">排行榜</a></li>
                    <li><a href="/others">围观</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li class="collapse navbar-collapse">
                        <p class="navbar-text">{{ $accountInfo["nickname"] }}</p>
                    </li>
                    <li><a href="/logout">退出</a></li>
                  </ul>
                </div><!-- /.navbar-collapse -->
                @endif

              </div><!-- /.container-fluid -->
            </nav>
        </header>

        <div class="container pub-content">
            @yield('content')
        </div>

        <footer class="container-fluid pub-footer">
             <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">Powered by Karoc Xing, Build by <a href="https://hyperf.wiki" target="_blank" >Hyperf</a>.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
             </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>

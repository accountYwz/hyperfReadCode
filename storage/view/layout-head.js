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
    password = CryptoJS.MD5(password).toString();
    const thisRSA = new RSA();

    thisRSA.passwordEncrypt(password);

    return CryptoJS.HmacSHA256(password + randomString, key).toString();
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

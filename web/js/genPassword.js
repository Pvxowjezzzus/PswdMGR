function genPswd() {
    var randomLength = Math.floor(Math.random() * (25 - 12) + 12);
    var password = "";
    var symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!№;%:?*()_+=";
    for (var i = 0; i < randomLength; i++){
        password += symbols.charAt(Math.floor(Math.random() * symbols.length));     
    }
    return document.querySelector('#passwords-plain_password').value = password;
}

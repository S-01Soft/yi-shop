
var userAgent = navigator.userAgent;
if (userAgent.indexOf('AlipayClient') > -1) {
    // 支付宝小程序的 JS-SDK 防止 404 需要动态加载，如果不需要兼容支付宝小程序，则无需引用此 JS 文件。
    document.writeln('<script src="https://appx/web-view.min.js"' + '>' + '<' + '/' + 'script>');
} else if (/QQ/i.test(userAgent) && /miniProgram/i.test(userAgent)) {
    // QQ 小程序
    document.write('<script type="text/javascript" src="{:cdnurl(\"__ADDON__/js/qqjssdk-1.0.0.js\",true)}"><\/script>');
} else if (/miniProgram/i.test(userAgent)) {
    // 微信小程序 JS-SDK 如果不需要兼容微信小程序，则无需引用此 JS 文件。
    document.write('<script type="text/javascript" src="{:cdnurl(\"__ADDON__/js/jweixin-1.4.0.js\",true)}"><\/script>');
} else if (/toutiaomicroapp/i.test(userAgent)) {
    // 头条小程序 JS-SDK 如果不需要兼容头条小程序，则无需引用此 JS 文件。
    document.write('<script type="text/javascript" src="{:cdnurl(\"__ADDON__/js/jssdk-1.0.1.js\",true)}"><\/script>');
} else if (/swan/i.test(userAgent)) {
    // 百度小程序 JS-SDK 如果不需要兼容百度小程序，则无需引用此 JS 文件。
    document.write('<script type="text/javascript" src="{:cdnurl(\"__ADDON__/js/swan-2.0.18.js\",true)}"><\/script>');
}
if (/toutiaomicroapp/i.test(userAgent)) {
    var el = document.querySelector('.post-message-section');
    if (el) el.style.display = 'none';
}
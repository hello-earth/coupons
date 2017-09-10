function initUserInfo(uid,url) {
    $.getJSON('./ctrl/getUserInfo.php?uid=' + uid, function (res) {
        $('#nickname').html(res.name);
        $('#tottalused').html(res.tottaltime);
        $('#todayused').html(res.todayused);
        $('#stillhave').html(res.stillhave);
        if(res.stillhave<0){
            $('#accountstatus').html("冻结");
        }
        if(res.name.length>=2){
            refreshLog(uid,url);
        }
    });
};

function refreshLog(uid,url) {
    $.getJSON('./ctrl/getUsedLog.php?uid=' + uid, function (res) {
        var hts = "";
        $('#cells_table').html(hts);
        for(var i=0;i<res.length;i++){
            var log = res[i];
            hts += '<div class="weui_cell"><div class="weui_cell_bd weui_cell_primary"><p>'+log.content+'</p></div><div class="weui_cell_bd weui_cell_primary">'+
                '<p>'+log.timestamp+'</p></div><div class="weui_cell_ft"><a href="javascript:alert('+log.pid+');" class="weui_btn weui_btn_mini weui_btn_warn">投诉</a>&nbsp;&nbsp;&nbsp;</div></div>';
        }
        if(res.length>0){
            $('#todaytstatus').html("今日新开"+res.length+"条记录");
        }
        $('#cells_table').html(hts);
        if(url.length>100){
            window.location = url;
        }
    });
    $('#loadingToast').fadeOut(200);
};

function setJSAPI(){
    var option = {
        title: '更好用的浦发红包机器人', // 分享标题
        desc: '更好用的浦发红包机器人，无需关注，无需收藏，即开即分享！\n服务2.0新升级，使用更畅快！', // 分享描述
        link: 'http://red.huakai.org/coupons/index.php', // 分享链接
        imgUrl: 'http://red.huakai.org/coupons/image/sharelogo.jpg' // 分享图标
    };

    $.getJSON('./ctrl/sign.php?url=' + encodeURIComponent(location.href.split('#')[0]), function (res) {
        wx.config({
            beta: true,
            debug: false,
            appId: res.appId,
            timestamp: res.timestamp,
            nonceStr: res.nonceStr,
            signature: res.signature,
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
            ]
        });
        wx.ready(function () {
            wx.onMenuShareTimeline(option);
            wx.onMenuShareQQ(option);
            wx.onMenuShareAppMessage(option);
            wx.checkJsApi({
                jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'],
                success: function(res) {
                    if(res.checkResult.onMenuShareTimeline && res.checkResult.onMenuShareAppMessage){
                        var uid =  $.trim(($("#uid").text()));
                        if(uid.length==10){
                            initUserInfo(uid);
                        }
                    }else{
                        window.location = "https://weixin.spdbccc.com.cn/wxrp-page-redpacketsharepage/share?packetId=0KZT1ZNWKPC6BIV0448125822-1504529265000c4c95038&noCheck=1&hash=109&dataDt=20170904";
                    }
                    // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                }
            });
        });
    });
}

function open_click() {
    var stillhave =  $.trim(($("#stillhave").text()));
    var todayused = $.trim(($("#todayused").text()));
    var js_dialog1 = $('#js_dialog1');
    var js_dialog2 = $('#js_dialog2');
    var js_dialog3 = $('#js_dialog3');
    var js_dialog4 = $('#js_dialog4');

    var uid =  $.trim(($("#uid").text()));
    if(todayused>=15){
        js_dialog1.fadeIn(200);
        return;
    }
    if(stillhave==0){
        js_dialog2.fadeIn(200);
        return;
    }
    if(stillhave<0){
        js_dialog3.fadeIn(200);
        return;
    }

    $('#loadingToast').fadeIn(200);
    $.getJSON('./ctrl/getOneMore.php?uid=' + uid, function (res) {
        if(res.r==1){
            $('#mymsg').html("<br>"+res.msg);
            js_dialog4.fadeIn(200);
        }else{
            initUserInfo(uid,res.url);
        }
    });
}


window.onload=function() {
    $('#loadingToast').fadeIn(200);
    setJSAPI();
    $('#dialogs').on('click', '.weui_dialog_confirm', function(){
        $(this).parents('.js_dialog').fadeOut(200);
    });
}
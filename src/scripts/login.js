(function(){
    prepare();
})();

$(function(){
    add_listener();
});

function add_listener(){
    $("#loginSubmitBtn").click(function(){
        if($(this).hasClass("disabled")){
            return;
        }

        if(!validateInput()){
            $("#loginErrorNotice").text("信息输入不完整").fadeIn();
        }else{
            loginAction();
        }
    });

    window.InputDetectAction = 'input propertychange';
    if(navigator.userAgent.indexOf("MSIE")>0){
        window.InputAction='keyup';
    }
    $(".login-input-ctrl").bind(window.InputDetectAction, function(e){
        
        
        $("#loginErrorNotice").fadeOut(800);
        if(validateInput()){
            $("#loginSubmitBtn").removeClass("disabled");
        }else{
            $("#loginSubmitBtn").addClass("disabled","disabled");
        }
    }).keypress(function(e){
        if(e.keyCode == 13 && validateInput()){
            $("#loginSubmitBtn").click();
            return false;
        }
    });

    $(".refresh-captcha").click(function(){
        var rawImgCodeUrl = $(".captcha-img").attr("raw-src");
        $(".captcha-img").attr("src",rawImgCodeUrl+"?"+Math.random());
    });
}

function validateInput(){
    var usernameIn = $("#userNameInput").val();
    var passwordIn = $("#passwordInput").val();
    var imageIn = $("#imageCodeInput").val();

    if(usernameIn.length > 18 || usernameIn.length < 2 || (!isNaN(usernameIn) && usernameIn.length != 11)){
        return false;
    }else if(passwordIn.length < 6){
        return false;
    }else if(imageIn.length != 4){
        return false;
    }

    return true;
}

function loginFeedbackShow(msg){
    $(".refresh-captcha").click();
    $("#loginErrorNotice").text(msg).fadeIn();
}

function loginAction(){
    var usernameIn = $("#userNameInput").val();
    var passwordIn = $("#passwordInput").val();
    var imageIn = $("#imageCodeInput").val();

    var loginParam = {};
    loginParam['username']=usernameIn;
    loginParam['password']=passwordIn;
    loginParam['code']=imageIn;
    loginParam['type']="more";
    $.ajax({
        url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["admin-login-service"],
        type:"POST",
        data:loginParam,
        dataType:"json",
        error : function(xmlHttpRequest, textStatus, errorThrown) {
            loginFeedbackShow("登录失败，请稍后再试");
        },
        beforeSend: function(){
            $("#loginErrorNotice").fadeOut();
            $("#loginSubmitBtn").addClass("disabled","disabled");
        },
        complete:function(){
            $("#loginSubmitBtn").removeClass("disabled");
        },
        success: function(jsonResponse){
            if(!jsonResponse.state){
                var loginNoticeStr = ((!jsonResponse.content) ? "登录失败，请稍后再试" : jsonResponse.content);
                loginFeedbackShow(loginNoticeStr);
                return;
            }
            loginFeedbackShow("登录成功");
            setTimeout(function(){jumpToSource()},500);
        }
    });
    //$("#loginErrorNotice").text("登录功能开发中").fadeIn();
}

function jumpToSource(){
    var queryArgs = $.getQueryArgs();
    var _QuickActionMap = {"fresh":"/anxin/anxin_fresh_project.php","experience_activity":"/activity/freshman/exp_intro.php","recommend_activity":"/recommend/monthly_recommend.php"};
    if(queryArgs.action && queryArgs.action in _QuickActionMap){
        window.location.href = _QuickActionMap[queryArgs.action];
    }else if(queryArgs.from){
        window.location.href = queryArgs.from;
    }else{
        window.location.href = window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL['admin-index'];
    }
}

function prepare(){
    window.InputDetectAction = 'input propertychange';
    if(navigator.userAgent.indexOf("MSIE")>0){
        window.InputAction='keyup';
    }

    $.extend({
        getQueryArgs:function () {
            var args = {};
            var query = location.search.substring(1);
            // Get query string
            var pairs = query.split("&");
            // Break at ampersand
            for(var i = 0; i < pairs.length; i++) {
                var pos = pairs[i].indexOf('=');
                // Look for "name=value"
                if (pos == -1) continue;
                // If not found, skip
                var argname = pairs[i].substring(0,pos);// Extract the name
                var value = pairs[i].substring(pos+1);// Extract the value
                value = decodeURIComponent(value);// Decode it, if needed
                args[argname] = value;
                // Store as a property
            }
            return args;// Return the object
        },
        formatSplitNumber : function(n){
            if (isNaN(n)) return;
            n = n+"";
            var str = n.split('.');
            str[0] = str[0] = str[0].replace(/\B(?=(?:[0-9]{3})+$)/g, ',');
            if (str[1] && parseInt(str[1])!=0) {
                var dcStr = str[1].substring(0,2);
                if (dcStr.length==1) return str[0]+"."+dcStr+"0";
                if (dcStr.length==2) return str[0]+"."+dcStr;
            } else {
                return str[0]+".00";
            }
        }
    });
}
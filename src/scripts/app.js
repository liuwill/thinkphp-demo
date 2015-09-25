(function(){
    prepare();
})();


$(function(){
    $(".sidebar-nav").click(function(){
        if($(this).hasClass("sidebar-nav-fold")){
            $(this).removeClass("sidebar-nav-fold");
        }else{
            $(this).addClass("sidebar-nav-fold");
        }
    });

    $(".dropdown-toggle").click(function(){
        var $dropdownContainer = $(this).parent(".dropdown");
        if($dropdownContainer.hasClass("open")){
            $dropdownContainer.removeClass("open");
        }else{
            $dropdownContainer.addClass("open");
        }
    });


    if(window._ADMIN_GLOBAL_CONFIG["module_name"]=="add_user"){
        __module_add_user();
    }else if(window._ADMIN_GLOBAL_CONFIG["module_name"]=="add_role"){
        __module_add_role();
    }else if(window._ADMIN_GLOBAL_CONFIG["module_name"]=="add_node"){
        __module_add_node();
    }else if(window._ADMIN_GLOBAL_CONFIG["module_name"]=="role_access"){
        __module_role_access();
    }else if(window._ADMIN_GLOBAL_CONFIG["module_name"]=="set_user_role"){
        __module_user_role_config();
    }else if(window._ADMIN_GLOBAL_CONFIG["module_name"]=="info:query_weather"){
        __module_info_display();
    }
});

/*添加新用户*/
function __module_add_user(){
    $("#createAccountBtn").click(function(){
        var username = $("#usernameInput").val();
        var password = $("#passwordInput").val();
        var email = $("#emailInput").val();
        var mobile = $("#mobileInput").val();

        if(!password || password.length < 6 || password.length>18){
            alert("用户密码最少6位");return;
        }
        if(!username || username.length < 3 || username.length>16 || !isNaN(username)){
            alert("用户名最少3位");return;
        }
        if(!mobile || mobile.length != 11 || isNaN(mobile)){
            alert("手机号格式错误");return;
        }

        var isEmail = function(str){
           var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
           return reg.test(str);
        }
        if(!email || !isEmail(email)){
            alert("邮箱格式不正确");return;
        }

        window._AddUserModule.createNewAccount({
            username:username,
            password:password,
            mobile:mobile,
            email:email,
            status:"active"
        });
    });
}

window._AddUserModule = {
    createNewAccount:function(accountParam){
        $.ajax({
            url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["create_new_account"],
            type:"POST",
            data:accountParam,
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                alert("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){
                //window.PageLocalLib.showMainContainer();
            },
            success: function(jsonResponse){
                if(!jsonResponse.state){
                    var noticeStr = ((!jsonResponse.content) ? "" : jsonResponse.content);
                    //window.PageLocalLib.showErrorNotice(loginNoticeStr);
                    alert(noticeStr);
                    return;
                }else{
                    alert("新用户添加成功");
                    window.location.href = window.location.href;
                }
                //window.PageLocalLib.analyseUserLocation(jsonResponse.content);
            }
        });
    }
};

/*添加角色*/
function __module_add_role(){
    $("#statusInput").click(function(){
        if($("#statusInput").attr("checked") == "checked"){
            $("#statusInput").removeAttr("checked")
        }else{
            $("#statusInput").attr("checked","checked")
        }
    });

    $("#addRoleBtn").click(function(){
        var rolename = $("#rolenameInput").val();
        var remark = $("#remarkInput").val();
        var status = 1;
        if($("#statusInput").attr("checked") != "checked"){
            status = 0;
        }

        window._AddRoleModule.addNewRole({
            name:rolename,
            remark:remark,
            status:status
        });
    });
}

window._AddRoleModule = {
    addNewRole:function(requestParam){
        $.ajax({
            url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["add_new_role"],
            type:"POST",
            data:requestParam,
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                alert("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){
                //window.PageLocalLib.showMainContainer();
            },
            success: function(jsonResponse){
                if(!jsonResponse.state){
                    var noticeStr = ((!jsonResponse.content) ? "" : jsonResponse.content);
                    //window.PageLocalLib.showErrorNotice(loginNoticeStr);
                    alert(noticeStr);
                    return;
                }else{
                    alert("角色添加成功");
                    window.location.href = window.location.href;
                }
                //window.PageLocalLib.analyseUserLocation(jsonResponse.content);
            }
        });
    }
};

/*添加功能节点*/
function __module_add_node(){
    window._AddNodeModule.loadNodeList();

    $("#statusInput").click(function(){
        if($("#statusInput").attr("checked") == "checked"){
            $("#statusInput").removeAttr("checked")
        }else{
            $("#statusInput").attr("checked","checked")
        }
    });

    $("#addNodeBtn").click(function(){
        var nodename = $("#nodenameInput").val();
        var remark = $("#nodeRemarkInput").val();
        var title = $("#nodeTitleInput").val();
        var status = 1;
        if($("#statusInput").attr("checked") != "checked"){
            status = 0;
        }
        var level = $("input[name='levelTypeRadio']:checked").val();
        var requestParam = {
            name:nodename,
            remark:remark,
            title:title,
            level:level,
            status:status
        };


        $(".extra-form-input").each(function(e){
            var pname = $(this).attr("name");
            var pvalue = $(this).val();
            requestParam[pname] = pvalue;
        });

        window._AddNodeModule.addNewNode(requestParam);
    });

    $("#pidInputSelect").change(function(){
        var defLevel = $("option:selected",this).attr('default-level');
        //var levelName = window._AddNodeModule.nodeLevelMap[defLevel];

        window._AddNodeModule.updateNodeType(defLevel);
    });

}

window._AddNodeModule = {
    nodeLevelMap:{
        "0":'root',
        "1":'application',
        "2":'controller',
        "3":'function',
    },nodeLevelType:{
        "0":'根节点',
        "1":'应用',
        "2":'控制器',
        "3":'动作方法',
    },updateNodeType:function(defLevel){
        var levelName = window._AddNodeModule.nodeLevelMap[defLevel];
        $('.node-type-radio').addClass('disabled');
        $("input[name='levelTypeRadio']:checked").removeAttr("checked");
        $("input[name='levelTypeRadio']").attr("disabled","disables");

        var $choosenTypeItem = $('.node-type-radio[node-type="'+levelName+'"]');
        var $chooseRadio = $("input",$choosenTypeItem);

        $choosenTypeItem.removeClass("disabled");
        $chooseRadio.removeAttr("disabled");
        $chooseRadio.click();
    },loadNodeList:function(){
        $.ajax({
            url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["node_list"],
            type:"GET",
            data:{},
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                console.log("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){},
            success: function(jsonResponse){
                if(!jsonResponse.state){
                    var noticeStr = ((!jsonResponse.content) ? "" : jsonResponse.content);
                    console.log(noticeStr);
                    return;
                }else{
                    var data_nodeList = jsonResponse.data;
                    for(var i in data_nodeList){
                        var single_node = data_nodeList[i];
                        var chile_level = single_node.level*1 + 1;
                        if(single_node.level > 2){
                            continue;
                        }
                        $("#pidInputSelect").append('<option value="'+single_node.id+'" default-level="'+chile_level+'">'+single_node.name+':'+single_node.remark+'['+window._AddNodeModule.nodeLevelType[single_node.level]+']</option>');
                    }
                    var queryArgs = $.getQueryArgs();
                    var pid = queryArgs.pid;
                    var $pidOption = $("#pidInputSelect > option[value='"+pid+"']");
                    if($pidOption.length){
                        //$("#pidInputSelect > option:checked").removeAttr();
                        $pidOption.attr("selected","selected");
                        var defLevel = $pidOption.attr('default-level');
                        window._AddNodeModule.updateNodeType(defLevel);
                    }
                }
            }
        });
    },addNewNode:function(requestParam){
        $.ajax({
            url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["add_new_node"],
            type:"POST",
            data:requestParam,
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                alert("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){
                //window.PageLocalLib.showMainContainer();
            },
            success: function(jsonResponse){
                if(!jsonResponse.state){
                    var noticeStr = ((!jsonResponse.content) ? "" : jsonResponse.content);
                    //window.PageLocalLib.showErrorNotice(loginNoticeStr);
                    alert(noticeStr);
                    return;
                }else{
                    alert("节点添加成功");
                    window.location.href = window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["node_manage"];//window.location.href;
                }
                //window.PageLocalLib.analyseUserLocation(jsonResponse.content);
            }
        });
    }
};

/*不同角色权限配置模块*/
function __module_role_access(){
    $(".node-check").click(function(){
        var clickLevel = $(this).attr("level");
        if(clickLevel == '1'){
            var $appInputs = $(this).parents('.app-body').find("input.node-check");

            if($(this).attr("checked")){
                $appInputs.removeAttr("checked");
            }else{
                $appInputs.each(function(){
                    //this.checked = true;
                    $(this).prop("checked","checked");
                    $(this).attr("checked","checked");
                });
            }
        }else if(clickLevel == '2'){
            var $appInputs = $(this).parents('.app-ctrl').find("input.node-check");

            if($(this).attr("checked")){
                $appInputs.removeAttr("checked");
            }else{
                $appInputs.each(function(){
                    //this.checked = true;
                    $(this).prop("checked","checked");
                    $(this).attr("checked","checked");
                });

                var $parentItem = $(this).parents('.app-body').find("input.node-check[level='1']");
                if(!$parentItem.attr("checked")){
                    $parentItem.prop("checked","checked");
                    $parentItem.attr("checked","checked");
                }
            }
        }else if(clickLevel == '3'){
            var $appInputs = $(this).parents('.app-body').find("input.node-check[level='1']");
            var $ctrlInputs = $(this).parents('.app-ctrl').find("input.node-check[level='2']");
            var parentItemList = [$appInputs,$ctrlInputs];

            for(var i in parentItemList){
                var $parentNode = parentItemList[i];
                if(!$parentNode.attr("checked")){
                    $parentNode.prop("checked","checked");
                    $parentNode.attr("checked","checked");
                }
            }
        }
    });

    $("#roleConfigBtn").click(function(e){
        var roleId = $(this).attr("role-id");
        var accessList = [];
        $(".node-check[name='access[]']").each(function(){
            var singleValue = $(this).val();

            if(this.checked){
                accessList.push(singleValue);
            }
        });
        //console.log(accessList);

        var requestParam = {
            role:roleId,
            access:accessList
        };

        window._RoleAccessModule.setRoleAccess(requestParam);
    });
}
window._RoleAccessModule = {
    setRoleAccess:function(requestParam){
        $.ajax({
            url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["set_role_access"],
            type:"POST",
            data:requestParam,
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                alert("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){
                //window.PageLocalLib.showMainContainer();
            },
            success: function(jsonResponse){
                if(!jsonResponse.state){
                    var noticeStr = ((!jsonResponse.content) ? "" : jsonResponse.content);
                    //window.PageLocalLib.showErrorNotice(loginNoticeStr);
                    alert(noticeStr);
                    return;
                }else{
                    alert("权限配置成功");
                    window.location.href = window.location.href;
                }
                //window.PageLocalLib.analyseUserLocation(jsonResponse.content);
            }
        });
    }
};

/*用户角色配置模块*/
function __module_user_role_config(){
    $(".role-check").click(function(){
        if($(this).attr("checked") == "checked"){
            $(this).removeAttr("checked")
        }else{
            $(this).attr("checked","checked")
        }

        if($(this).attr("name")=="all-role"){
            if($(this).attr("checked") == "checked"){
                $(".role-check[name='roles[]']").prop("checked","checked");
                $(".role-check[name='roles[]']").attr("checked","checked");
            }
        }
    });

    $("#userRoleSetBtn").click(function(e){
        var userId = $(this).attr("user-id");
        var rolesList = [];
        $(".role-check[name='roles[]']").each(function(){
            var singleValue = $(this).val();

            if(this.checked){
                rolesList.push(singleValue);
            }
        });
        //console.log(accessList);

        var requestParam = {
            user:userId,
            roles:rolesList
        };

        window._UserRoleConfigModule.setUserRoleConfig(requestParam);
    });
}
window._UserRoleConfigModule = {
    setUserRoleConfig:function(requestParam){
        $.ajax({
            url:window._ADMIN_API_URL["admin_server_addr"]+window._ADMIN_API_URL["set_user_role_handle"],
            type:"POST",
            data:requestParam,
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                alert("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){
                //window.PageLocalLib.showMainContainer();
            },
            success: function(jsonResponse){
                if(!jsonResponse.state){
                    var noticeStr = ((!jsonResponse.content) ? "" : jsonResponse.content);
                    //window.PageLocalLib.showErrorNotice(loginNoticeStr);
                    alert(noticeStr);
                    return;
                }else{
                    alert("用户角色配置成功");
                    window.location.href = window.location.href;
                }
                //window.PageLocalLib.analyseUserLocation(jsonResponse.content);
            }
        });
    }
};

/* *简单案例展示* */
function __module_info_display(){
    $(".info-query-btn").click(function(){
        var queryType = $(this).attr("query-type");
        var queryKey = $("#queryKeyIn").val();

        if(!queryKey){
            return;
        }
        var requestParam = {
            key:queryKey,
            method:queryType
        };
        window._InfoDisplayModule.querySingleData(requestParam);
    });

    /*$.ajax({
        url: window._ADMIN_API_URL["admin_server_addr"]+window._InfoDisplayModule['baidu-cityifo'],
        data:{key:"玉溪"},
        type: 'GET',
        beforeSend: function(XMLHttpRequest) {},
        dataType: 'JSON',//here
        success: function (jsonResponse) {
            console.log(jsonResponse);

            $(".result-show-block > pre").html(JSON.stringify(jsonResponse.data));
        }
    });*/
}

window._InfoDisplayModule = {
    url_list:{
        "city":"Info/get_baidu_city_info",
        "weather":"Info/get_baidu_weather_info",
        "ip":"Info/get_taobao_ip_info"
    },
    querySingleData:function(requestParam){
        var queryUrl = window._ADMIN_API_URL["admin_server_addr"]+window._InfoDisplayModule.url_list[requestParam.method];
        $.ajax({
            url:queryUrl,
            type:"GET",
            data:requestParam,
            dataType:"json",
            error : function(xmlHttpRequest, textStatus, errorThrown) {
                $(".result-show-block > pre").html("请求失败");
                //window.PageLocalLib.showErrorNotice("");
            },
            beforeSend: function(){},
            complete:function(){
                //window.PageLocalLib.showMainContainer();
            },
            success: function(jsonResponse){
                $(".result-show-block > pre").html(JSON.stringify(jsonResponse));
                //window.PageLocalLib.analyseUserLocation(jsonResponse.content);
            }
        });
    }
};


/** ** 加载的公共模块类库（Jquery） ** **/
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
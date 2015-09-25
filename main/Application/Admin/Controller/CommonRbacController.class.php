<?php
//namespace Common\Controller;
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

abstract class CommonRbacController extends Controller {
    /*public function _empty($name){
        //把所有城市的操作解析到city方法
        echo $name;
    }*/
    
    protected function assignCommon(){
        $adminUserData = json_decode($_SESSION['admin_auth_user'],true);
        $this->assign("username",$adminUserData["username"]);
        $this->assign("adminMobile",$adminUserData["mobile"]);
        if(empty($adminUserData["email"])){
            $this->assign("adminEmail","liuwei_will@qq.com");
        }else{
            $this->assign("adminEmail",$adminUserData["email"]);
        }
        $this->assign("globalEnv","dev");
        
        $this->adminUserData = $adminUserData;
    }
    
    protected function displayDashboard($dashConfig = array("moduleName"=>"index_main","mainBodyPath"=>"./Public/templete/admin/index_main.html")){
        if(empty($dashConfig) || !is_array($dashConfig)){
            $dashConfig = array("moduleName"=>"index_main","mainBodyPath"=>"./Public/templete/admin/index_main.html");
        }elseif(!isset($dashConfig['mainBodyPath']) || !isset($dashConfig['moduleName'])){
            $dashConfig = array("moduleName"=>"index_main","mainBodyPath"=>"./Public/templete/admin/index_main.html");
        }
        
        $this->assign("mainBodyPath",$dashConfig['mainBodyPath']);
        $this->assign("moduleName",$dashConfig['moduleName']);
        $this->assign("globalEnv","dev");
        $this->display("Index:index");
    }
    
    protected function checkAdminLogin(){
        if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
            import ( 'Org.Util.Rbac' );
            if (! Rbac::checkLogin ()) {
                //检查认证识别号
                if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
                    //跳转到认证网关
                    redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                }
                // 没有权限 抛出错误
                if (C ( 'RBAC_ERROR_PAGE' )) {
                    // 定义权限错误页面
                    redirect ( C ( 'RBAC_ERROR_PAGE' ) );
                } else {
                    if (C ( 'GUEST_AUTH_ON' )) {
                        $this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                    }
                    // 提示错误信息
                    $this->error ( L ( '_VALID_ACCESS_' ) );
                }
            }
        }
    }
    
    protected function checkAdminLoginREST(){
        if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
            import ( 'Org.Util.Rbac' );
            if (! Rbac::checkLogin ()) {
                //检查认证识别号
                if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
                    //跳转到认证网关
                    $this->ajaxReturn(array("state"=>false,"content"=>"用户没有登录"));
                    //redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                }
                // 没有权限 抛出错误
                if (C ( 'RBAC_ERROR_PAGE' )) {
                    // 定义权限错误页面
                    $this->ajaxReturn(array("state"=>false,"content"=>"用户没有登录"));
                    //redirect ( C ( 'RBAC_ERROR_PAGE' ) );
                } else {
                    if (C ( 'GUEST_AUTH_ON' )) {
                        $this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                    }
                    $this->ajaxReturn(array("state"=>false,"content"=>"用户没有登录"));
                    // 提示错误信息
                    //$this->error ( L ( '_VALID_ACCESS_' ) );
                }
            }
        }
    }
    
    protected function checkRbacAuthREST(){
        if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
            import ( 'Org.Util.Rbac' );
            if (! Rbac::AccessDecision ()) {
                //检查认证识别号
                if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
                    //跳转到认证网关
                    //redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                    $this->ajaxReturn(array("state"=>false,"content"=>"没有足够权限进行该项操作"));
                    return false;
                }
                // 没有权限 抛出错误
                if (C ( 'RBAC_ERROR_PAGE' )) {
                    // 定义权限错误页面
                    //redirect ( C ( 'RBAC_ERROR_PAGE' ) );
                    $this->ajaxReturn(array("state"=>false,"content"=>"没有足够权限进行该项操作"));
                    return false;
                } else {
                    if (C ( 'GUEST_AUTH_ON' )) {
                        $this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                    }
                    // 提示错误信息
                    $this->ajaxReturn(array("state"=>false,"content"=>"没有足够权限进行该项操作"));
                    return false;
                    //$this->error ( L ( '_VALID_ACCESS_' ) );
                }
            }
        }
        return true;
    }
    
    protected function checkRbacAuth(){
        if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
            import ( 'Org.Util.Rbac' );
            if (! Rbac::AccessDecision ()) {
                //检查认证识别号
                if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
                    //跳转到认证网关
                    redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                }
                // 没有权限 抛出错误
                if (C ( 'RBAC_ERROR_PAGE' )) {
                    // 定义权限错误页面
                    redirect ( C ( 'RBAC_ERROR_PAGE' ) );
                } else {
                    if (C ( 'GUEST_AUTH_ON' )) {
                        $this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
                    }
                    // 提示错误信息
                    $this->error ( L ( '_VALID_ACCESS_' ) );
                }
            }
        }
    }
}
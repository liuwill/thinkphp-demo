<?php
namespace Admin\Controller;
//use Think\Controller;
//use Org\Util\Rbac;
class UserController extends CommonRbacController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function user_manage(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/user_manage.html");
        $this->assign("moduleName","user_manage");
        
        
        $adminModel = M("admin_user");
        $adminUserList = $adminModel->select();
        $this->assign("adminUserList",$adminUserList);
        $this->display("Index:index");
    }
    
    public function add_user(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/add_user.html");
        $this->assign("moduleName","add_user");
        $this->assign("globalEnv","dev");
        $this->display("Index:index");
    }
    
    public function create_account(){
        if(!$this->checkRbacAuthREST()){
            return;
        }
        
        $username = $_REQUEST["username"];
        $password = $_REQUEST["password"];
        $mobile = $_REQUEST["mobile"];
        $email = $_REQUEST["email"];
        
        if(is_numeric($username) || strlen($username)>16 || strlen($username)<3){
            $this->ajaxReturn(array("state"=>false,"content"=>"用户名格式不正确"));
            return;
        }elseif(strlen($password)>18 || strlen($password)<6){
            $this->ajaxReturn(array("state"=>false,"content"=>"密码格式不正确"));
            return;
        }elseif(!is_numeric($mobile) || strlen($mobile)!=11){
            $this->ajaxReturn(array("state"=>false,"content"=>"手机号格式不正确"));
            return;
        }
        $regEmail="/^[0-9a-zA-Z]+(?:[_-][a-z0-9-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*.[a-zA-Z]+$/i";  
        
        if(empty($email) || !preg_match($regEmail,$email)){
            $this->ajaxReturn(array("state"=>false,"content"=>"邮箱格式不正确"));
            return;
        }
        
        $addUserResult = M("admin_user")->add(array(
            "username"=>$username,
            "password"=>md5($password),
            "mobile"=>$mobile,
            "email"=>$email,
            "type"=>"user",
            "regtime"=>date("Y-m-d H:i:s"),
        ));
        if($addUserResult){
            $this->ajaxReturn(array("state"=>true,"content"=>"添加成功"));
        }else{
            $this->ajaxReturn(array("state"=>false,"content"=>"创建新用户失败","error"=>$addUserResult));
        }        
    }
}
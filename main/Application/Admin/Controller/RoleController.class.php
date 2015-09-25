<?php
namespace Admin\Controller;
//use Think\Controller;
//use Org\Util\Rbac;

class RoleController extends CommonRbacController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function set_user_role(){
        $this->checkAdminLogin();
        $userId = $_REQUEST["user"];
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/set_user_role.html");
        $this->assign("moduleName","set_user_role");
        $this->assign("globalEnv","dev");
        
        $userModel = M("admin_user");
        $userData = $userModel->where(array("uid"=>$userId))->find();
        if(empty($userData) || !$userData){
            $this->error("用户不存在");
        }
        
        $roleModel = M("think_role");
        $allRoleList = $roleModel->select();
        
        $userRoleModel = M("think_role_user");
        $userRoleList = $userRoleModel->where(array("user_id"=>$userId))->select();
        
        $userRoleGroup = merge_user_role($allRoleList,$userRoleList);
        
        $this->assign("userRoleGroup",$userRoleGroup);
        $this->assign("userData",$userData);
        $this->display("Index:index");
    }
    
    public function set_user_role_handle(){
        if(!$this->checkRbacAuthREST()){
            return;
        }
        $userId = $_REQUEST["user"];
        $userModel = M("admin_user");
        $userInfo = $userModel->where(array("uid"=>$userId))->find();
        if(empty($userInfo) || !$userInfo){
            $this->ajaxReturn(array("state"=>false,"content"=>"不存在对应的用户"));
        }
        
        $userRoleModel = M("think_role_user");
        $userRoleModel->where(array("user_id"=>$userId))->delete();
        
        $roleData = array();
        foreach($_POST[roles] as $roleValue){
            $roleData[]=array(
                "role_id"=>$roleValue,
                "user_id"=>$userId
            );
        }
        
        $modelResult = $userRoleModel->addAll($roleData);
        if($modelResult){
            $this->ajaxReturn(array("state"=>true,"content"=>"角色更新成功"));
        }else{
            $this->ajaxReturn(array("state"=>false,"content"=>"角色配置失败","error"=>$modelResult));
        }
        
    }
    
    public function role_access(){
        $this->checkAdminLogin();
        $roleId = $_REQUEST["role"];
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/role_access.html");
        $this->assign("moduleName","role_access");
        $this->assign("globalEnv","dev");
        
        $nodeModel = M("think_node");
        $nodeItemList = $nodeModel->order('sort')->select();
        
        $accessModel = M("think_access");
        $accessItemList = $accessModel->where(array("role_id"=>$roleId))->getField('node_id',true);
        
        $nodeAccessGroup = node_access_merge($nodeItemList,$accessItemList);
        //$this->assign("nodeItemList",$nodeItemList);
        $this->assign("nodeAccessGroup",$nodeAccessGroup);
        
        $roleModel = M("think_role");
        $roleData = $roleModel->where(array("id"=>$roleId))->find();
        $this->assign("roleData",$roleData);
        
        $this->display("Index:index");
    }
    
    public function set_access(){
        if(!$this->checkRbacAuthREST()){
            return;
        }
        $roleId = $_REQUEST["role"];
        $roleModel = M("think_role");
        $roleInfo = $roleModel->where(array("id"=>$roleId))->find();
        if(empty($roleInfo) || !$roleInfo){
            $this->ajaxReturn(array("state"=>false,"content"=>"不存在对应的角色"));
        }
        
        $accessModel = M("think_access");
        $accessModel->where(array("role_id"=>$roleId))->delete();
        
        $roleData = array();
        foreach($_POST[access] as $accessValue){
            $tmpArr = explode("_",$accessValue);
            $roleData[]=array(
                "role_id"=>$roleId,
                "node_id"=>$tmpArr[0],
                "level"=>$tmpArr[1]
            );
        }
        
        $modelResult = $accessModel->addAll($roleData);
        if($modelResult){
            $this->ajaxReturn(array("state"=>true,"content"=>"权限更新成功"));
        }else{
            $this->ajaxReturn(array("state"=>false,"content"=>"权限配置失败","error"=>$modelResult));
        }
    }
    
    public function add_node(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/add_node.html");
        $this->assign("moduleName","add_node");
        $this->assign("globalEnv","dev");
        
        $this->display("Index:index");
    }
    
    public function node_list(){
        $this->checkAdminLoginREST();
        
        $nodeModel = M("think_node");
        $nodeItemList = $nodeModel->order('sort')->select();
        //p($nodeItemList);
        $this->ajaxReturn(array("state"=>true,"content"=>"获取成功","data"=>$nodeItemList));
    }
    
    public function node_group(){
        $this->checkAdminLoginREST();
        
        $field = array('id','name','title','pid','level');
        $nodeModel = M("think_node");
        $nodeItemList = $nodeModel->field($field)->order('sort')->select();
        //p($nodeItemList);
        $nodeItemList = node_merge($nodeItemList);
        
        $this->ajaxReturn(array("state"=>true,"content"=>"获取成功","data"=>$nodeItemList));
    }
    
    public function node_manage(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/node_manage.html");
        $this->assign("moduleName","node_manage");
        $this->assign("globalEnv","dev");
        
        $nodeModel = M("think_node");
        $nodeItemList = $nodeModel->order('sort')->select();
        
        $nodeItemGroup = node_merge($nodeItemList);
        $this->assign("nodeItemList",$nodeItemList);
        $this->assign("nodeItemGroup",$nodeItemGroup);
        $this->display("Index:index");
    }
    
    public function add_node_handle(){
        if(!$this->checkRbacAuthREST()){
            return;
        }
        
        $name = $_REQUEST["name"];
        $remark = $_REQUEST["remark"];
        $status = $_REQUEST["status"];
        $title = $_REQUEST["title"];
        $sort = $_REQUEST["sort"];
        $pid = $_REQUEST["pid"];
        $level = $_REQUEST["level"];
        
        if($status !== "0" && $status !== "1"){
            $this->ajaxReturn(array("state"=>false,"content"=>"开启状态错误"));
            return;
        }elseif(empty($remark) || strlen($remark)<6 || strlen($remark)>255){
            $this->ajaxReturn(array("state"=>false,"content"=>"节点描述不正确"));
            return;
        }elseif(empty($name) || strlen($name)<3 || strlen($name)>20){
            $this->ajaxReturn(array("state"=>false,"content"=>"节点名称不正确"));
            return;
        }elseif(empty($title) || strlen($title)<3 || strlen($title)>50){
            $this->ajaxReturn(array("state"=>false,"content"=>"节点Title不正确"));
            return;
        }
        
        $operationResult = M("think_node")->add(array(
            "name"=>$name,
            "remark"=>$remark,
            "status"=>$status,
            "title"=>$title,
            "sort"=>$sort,
            "pid"=>$pid,
            "level"=>$level
        ));
        if($operationResult){
            $this->ajaxReturn(array("state"=>true,"content"=>"添加node成功"));
        }else{
            $this->ajaxReturn(array("state"=>false,"content"=>"添加node失败","error"=>$operationResult));
        }
    }
    
    public function role_manage(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/role_manage.html");
        $this->assign("moduleName","role_manage");
        $this->assign("globalEnv","dev");
        
        $roleModel = M("think_role");
        $roleItemList = $roleModel->select();
        $this->assign("roleItemList",$roleItemList);
        $this->display("Index:index");
    }
    
    public function add_role(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/add_role.html");
        $this->assign("moduleName","add_role");
        $this->assign("globalEnv","dev");
        
        $this->display("Index:index");
    }
    
    public function add_role_handle(){
        if(!$this->checkRbacAuthREST()){
            return;
        }
        
        $name = $_REQUEST["name"];
        $remark = $_REQUEST["remark"];
        $status = $_REQUEST["status"];
        
        if($status !== "0" && $status !== "1"){
            $this->ajaxReturn(array("state"=>false,"content"=>"开启状态错误"));
            return;
        }elseif(empty($remark) || strlen($remark)<6 || strlen($remark)>255){
            $this->ajaxReturn(array("state"=>false,"content"=>"角色描述不正确"));
            return;
        }elseif(empty($name) || strlen($name)<3 || strlen($name)>20){
            $this->ajaxReturn(array("state"=>false,"content"=>"角色名称不正确"));
            return;
        }
        
        $addRoleResult = M("think_role")->add(array(
            "name"=>$name,
            "remark"=>$remark,
            "status"=>$status
        ));
        if($addRoleResult){
            $this->ajaxReturn(array("state"=>true,"content"=>"添加角色成功"));
        }else{
            $this->ajaxReturn(array("state"=>false,"content"=>"添加角色失败","error"=>$addRoleResult));
        }
    }

}
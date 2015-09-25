<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)liuwill</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function hello(){
        $data['config']  = C("adminname"); 
        $data['home']  = C('module_name'); 
        $data['home_config']  = C('home_config.module_name',NULL,'DEFAULT'); 
        $data['status']  = 1;
        $data['content'] = 'content';
        $this->ajaxReturn($data);
    }
    
    public function session(){
        dump(session());
    }
    
    public function database(){
        //$User = new \Home\Model\UserModel();
        $data['user'] = M()->db(1,"EX_LOCALE_DB_CONFIG")->query("select * from usertb where uid=1001");;//$User->query("select * from usertb where uid=1001");
        
        session("username",$data['user'][0]["username"]);
        $data['status']  = 1;
        $data['content'] = 'content';
        $this->ajaxReturn($data);
    }
    
    public function noban(){
        //import('@.Model.ExternalUserModel');
        //$nobanModel = new \Home\Model\ExternalUserModel();
        $nobanModel = D("ExternalUser");
        $nobanModel->db(1,"EX_LOCALE_DB_CONFIG");
        $article_list = $nobanModel->where("usertb.uid<'1004'")->select();
        $data['user'] = $article_list;//$User->query("select * from usertb where uid=1001");
        $data['status']  = 1;
        $data['content'] = 'content';
        $this->ajaxReturn($data);
    }
    
    public function page(){
        $nobanModel = D("ExternalUser");
        $user_list = $nobanModel->where("usertb.uid<'1004'")->select();
        
        $this->assign('userList',$user_list);
        $this->display();

    }
    
    public function user(){
        //$User = new \Home\Model\UserModel();
        $Dao = M('Usertb',"","EX_LOCALE_DB_CONFIG"); // 实例化视图
        //$Dao = M('User'); // 实例化视图
        $article_list = $Dao->find(1001);
        $data['user'] = $article_list;//$User->query("select * from usertb where uid=1001");
        $data['status']  = 1;
        $data['content'] = 'content';
        $this->ajaxReturn($data);
    }
}
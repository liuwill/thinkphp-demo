<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
use Common\Library\Util\ValidateCode;

class PublicController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)liuwill</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function login(){
        if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
            //$heroImagePath = "../static/images/hero/";
            //$imgDict = list_dir_fileName($heroImagePath); 
            
            $awsImagePath = C("LOGIN_HERO_PREDIX");
            $imgDict = C("LOGIN_HERO_LIST");
            $imgPos = rand(0,count($imgDict)-1);
            $imageName = $imgDict[$imgPos];
            //$this->assign("backImgUrl","/static/images/hero/{$imageName}");
            $this->assign("backImgUrl","{$awsImagePath}{$imageName}");
            $this->display();
        } else {
            $this->redirect('Index/index');
        }
    }
        
    public function login_request(){
        $userName = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        
        if (empty($_REQUEST['username'])) {
            $this->ajaxReturn(array("state"=>false,"message"=>"用户名错误","content"=>"用户名错误"));
        } elseif (empty($_REQUEST['password'])) {
            $this->ajaxReturn(array("state"=>false,"message"=>"密码错误","content"=>"密码错误"));
        } 
        
        //生成认证条件
        $map = array();
        
        // 支持使用绑定帐号登录
        $map['username'] = $userName;
        import('Org.Util.Rbac');
        $authInfo = Rbac::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if (false === $authInfo) {
            $this->ajaxReturn(array("state"=>false,"message"=>"帐号不存在或已禁用","content"=>"帐号不存在或已禁用"));
            //$this->error('帐号不存在或已禁用！');
        } else {
            if ($authInfo['password'] != md5($password)) {
                $this->ajaxReturn(array("state"=>false,"message"=>"密码错误！","content"=>"密码错误！","data"=>$authInfo));
                //$this->error('密码错误！');
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo[C('USER_AUTH_KEY')];
            if (C('ADMIN_AUTH_KEY') == _uid_mask($authInfo[C('USER_AUTH_KEY')])) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }
            $_SESSION['admin_auth_user'] = json_encode($authInfo);
            
            // 缓存访问权限
            Rbac::saveAccessList();
            $this->ajaxReturn(array("state"=>true,"message"=>"登录成功！","content"=>"登录成功！"));
            //$this->success('登录成功！');
            //$this->redirect('Index/index');
        }
    }
    
    public function loginout() {
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            unset($_SESSION['admin_auth_user']);
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
            //$this->assign("jumpUrl", __URL__ . '/login/');
            $this->assign("jumpUrl", U('Admin/Public/login'));
            $this->success('登出成功！');
        } else {
            $this->assign("jumpUrl", U('Admin/Public/login'));
            $this->error('已经登出！');
        }
    }
    
    public function captcha() {
        $_vc = new ValidateCode();      
        $_vc->doimg();
        session('loginCapImgCode',$_vc->getCode());//验证码保存到SESSION中 
        //$_SESSION['code'] = $_vc->getCode();
    }
    
    public function hello(){
        $data['config']  = C("adminname"); 
        $data['home']  = C('module_name'); 
        $data['home_config']  = C('home_config.module_name',NULL,'DEFAULT'); 
        $data['status']  = 1;
        $data['content'] = 'content';
        $this->ajaxReturn($data);
    }
}


<?php
namespace Admin\Controller;
//use Common\Controller;
//use Think\Controller;
//use Org\Util\Rbac;

class InfoController extends CommonRbacController {
    
    public function index(){
        $this->checkAdminLogin();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/admin/index_main.html");
        $this->assign("moduleName","main");
        $this->assign("globalEnv","dev");
        $this->display("Index:index");
        /*$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');*/
    }
    
    public function hello(){
        $data['config']  = C("adminname"); 
        $data['home']  = C('module_name'); 
        $data['home_config']  = C('home_config.module_name',NULL,'DEFAULT'); 
        $data['status']  = 1;
        $data['content'] = 'content';
        $this->ajaxReturn($data);
    }
    
    public function query_weather(){
        $this->checkRbacAuth();
        
        $this->assignCommon();
        
        $this->assign("mainBodyPath","./Public/templete/info/query_weather.html");
        $this->assign("moduleName","info:query_weather");
        $this->assign("globalEnv","dev");
        
        $this->display("Index:index");
    }
    
    public function get_taobao_ip_info(){
        $this->checkAdminLogin();
        //header("Content-Type:application/json;charset=UTF-8");
        //header("Access-Control-Allow-Origin:*");
        
        $ip = $_REQUEST["key"];
        $data=json_decode($this->get_url_contents("http://ip.taobao.com/service/getIpInfo.php?ip={$ip}"),true);
        if($data["code"] != "0"){
            $this->ajaxReturn(array("state"=>false,"content"=>"获取数据失败","data"=>$data));
        }
        $this->ajaxReturn(array("state"=>true,"content"=>$data["data"],"data"=>$data));
        //echo @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$_REQUEST["ip"]);
    }
    
    public function get_baidu_weather_info(){
        $this->checkAdminLogin();
        $cityId = $_REQUEST["key"];
        
        $data=json_decode($this->get_url_contents("http://apistore.baidu.com/microservice/weather?cityid={$cityId}"),true);
        if($data["errMsg"] !== "success"){
            $this->ajaxReturn(array("state"=>false,"content"=>"获取数据失败","data"=>$data));
        }
        $this->ajaxReturn(array("state"=>true,"content"=>$data["retData"],"data"=>$data["retData"]));
    }
    
    public function get_baidu_city_info(){
        $this->checkAdminLogin();
        $cityName = $_REQUEST["key"];
        
        $data=json_decode($this->get_url_contents("http://apistore.baidu.com/microservice/cityinfo?cityname={$cityName}"),true);
        if($data["retMsg"] !== "success"){
            $this->ajaxReturn(array("state"=>false,"content"=>"获取数据失败","data"=>$data));
        }
        $this->ajaxReturn(array("state"=>true,"content"=>$data["retData"],"data"=>$data["retData"]));
    }
    
    private function get_url_contents($urlAddr) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $urlAddr);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result =  curl_exec($ch);
        curl_close($ch);
        return $result;
     }
    
    
}
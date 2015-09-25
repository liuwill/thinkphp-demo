<?php

function node_merge($node,$pid = 0){
    $arr = array();
    
    foreach($node as $v){
        if($v['pid'] == $pid){
            $v['child'] = node_merge($node,$v['id']);
            $arr[] = $v;
        }
    }
    return $arr;
}

function node_access_merge($node,$access = null,$pid = 0){
    $arr = array();
    
    foreach($node as $v){
        $v['access'] = false;
        if(is_array($access) && in_array($v['id'],$access)){
            $v['access'] = true;
        }
        
        if($v['pid'] == $pid){
            $v['child'] = node_access_merge($node,$access,$v['id']);
            $arr[] = $v;
        }
    }
    return $arr;
}

function merge_user_role($roleList,$userRoleList = null){
    $userRoleDict = array();
    if(count($userRoleList)){
        foreach($userRoleList as $singleRole){
            $userRoleDict["{$singleRole["role_id"]}"] = true;
        }
    }
    
    $userRoleGroup = array();
    foreach($roleList as $singleRole){
        $isAuth = false;
        if(isset($userRoleDict["{$singleRole['id']}"]) && $userRoleDict["{$singleRole['id']}"] === true){
            $isAuth = true;
        }
        
        $userRoleGroup[] = array(
            "role_id"=>$singleRole['id'],
            "name"=>$singleRole['name'],
            "pid"=>$singleRole['pid'],
            "status"=>$singleRole['status'],
            "remark"=>$singleRole['remark'],
            "auth"=>$isAuth
        );
    }
    return $userRoleGroup;
}
<?php
return array(
	//'配置项'=>'配置值'
    'URL_MODEL'          => '4',    //
    'URL_HTML_SUFFIX'    => '', //不添加任何伪扩展名
    'adminname'=>'adminname',
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'dbschema', // 数据库名
    'DB_USER'   => 'username', // 用户名
    'DB_PWD'    => 'password', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'EX_LOCALE_DB_CONFIG'=> array(
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => '127.0.0.1', // 服务器地址
        'DB_NAME'   => 'externalserver', // 数据库名
        'DB_USER'   => 'locale', // 用户名
        'DB_PWD'    => '123456', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PREFIX' => '', // 数据库表前缀 
        'DB_CHARSET'=> 'utf8', // 字符集
        'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    ),
    'APP_AUTOLOAD_PATH'=>'@.TagLib',
    'SESSION_AUTO_START'=>true,
    'USER_AUTH_ON'              =>true,
    'USER_AUTH_TYPE'            =>1,     // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>'uid',  // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'            =>'u1000',
    'USER_AUTH_MODEL'           =>'admin_user',    // 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>'md5', // 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>'/Admin/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>'Public',  // 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>'',        // 默认需要认证模块
    'NOT_AUTH_ACTION'           =>'',        // 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>'',        // 默认需要认证操作
    'GUEST_AUTH_ON'             =>false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>0,        // 游客的用户ID
    'DB_LIKE_FIELDS'            =>'title|remark',
    'RBAC_ROLE_TABLE'           =>'think_role',
    'RBAC_USER_TABLE'           =>'think_role_user',
    'RBAC_ACCESS_TABLE'         =>'think_access',
    'RBAC_NODE_TABLE'           =>'think_node',
    'SHOW_PAGE_TRACE'=>1//显示调试信息
);
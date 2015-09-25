# ThinkPHP RBAC演示系统 

> 基于ThinkPHP RBAC模块开发的演示程序

> 演示网址:[Demo](http://www.liuwill.com/thinkphp/index.php/Admin/Index)


## 项目说明
该项目是为了展示使用 `ThinkPHP RBAC` 模块而编写的简单演示系统，通过操作角色和权限相关表，实现后台模块的基于角色的权限管理。该系统使用`Jquery+Bootstrap`作为前端框架，界面风格是仿阿里云的管理控制台实现的；目前未增加响应式支持。页面渲染基本采用ThinkPHP视图模版，只有功能使用JS交互代码。

实现过程中，对前端进行了类似SPA的模块化划分，在配置文件中接收控制器中注册的模块名，然后动态加载模块对应的交互代码；整个前端模版，只有一个主模块页面，包含了公共的界面框架；控制器中根据注册的模块名，加载对应的子模块模版，模版的代码位于`main/thinkphp/Public/templete`中，预计今后可能会移到`src/templete`中，部署的时候由自动化构建工具打包到Public目录下的templete。

### 自动化构建

系统采用自动化构建工作流来实现项目的编译和打包，将`main`和`src`下面的文件先在`build`中进行预处理，然后全部打包到`deploy`目录，之后在该目录中就可以运行，将`deploy/thinkphp`、`deploy/static`分别拷贝到apache虚拟目录的根目录下，就可以访问系统。

自动化构建工具使用的是`grunt`，会对js代码进行压缩，然后将sass编译成css，进过压缩之后拷贝到最后的部署目录，过程中，还要将依赖文件加上md5验证码，然后替换模版中对静态文件的引用。然后把bower管理的模块，还有thinkPHP框架也一起打包到部署目录，在这个系统中，还会使用grunt-php可以直接轻量化的运行打包好的代码

###### 运行命令

`grunt prepare` - 执行clean:build命令，清除build和deploy

`grunt build` - 编译sass和js，并且将需要编译处理的源文件拷贝到build中，对build中的中间文件进行处理，得到最终的文件

`grunt deploy` - 将bower管理的包，还有编译过的静态文件，thinkPHP框架，打包到deploy目录

`grunt execute` - 运行deploy中已经单独进行过打包的项目

`grunt deploy_pack` - 通过prepare、build、deploy对项目进行一键打包

`grunt execute_all` - 一键打包，并且用grunt-php来运行项目

### 配置说明

在这里，需要通过对thinkPHP进行配置，来设置运行时的一些特性，在thinkPHP下面的`Application/Common/Conf/config.php`。代码中配置文件的命名是`config_dev.php`需要修改文件名，改成`config.php`。

``` thinkPHP配置文件
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
    'APP_AUTOLOAD_PATH'=>'@.TagLib',
    'SESSION_AUTO_START'=>true,
    'USER_AUTH_ON'              =>true,
    'USER_AUTH_TYPE'            =>1,     // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>'uid',  // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'            =>'u1000', //如果think_role_user中的user_id使用的是数字，需要和字符串拼接
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
```

#### 运行命令

`grunt prepare` - 执行clean:build命令，清除build和deploy

`grunt build` - 编译sass和js，并且将需要编译处理的源文件拷贝到build中，对build中的中间文件进行处理，得到最终的文件

`grunt deploy` - 将bower管理的包，还有编译过的静态文件，thinkPHP框架，打包到deploy目录

`grunt execute` - 运行deploy中已经单独进行过打包的项目

`grunt deploy_pack` - 通过prepare、build、deploy对项目进行一键打包

`grunt execute_all` - 一键打包，并且用grunt-php来运行项目

#### 系统访问路径
Url: `/thinkphp/Admin/Index/index`  
Desc: 系统控制台首页

包括角色管理功能，还有提供的几个演示功能。

##### 登录页面
Url: `/thinkphp/Admin/Public/login`  
Desc: 登录页面

### 目录文件说明

thinkPHP代码，位于main目录下；整个控制台只有一个主要的视图模版`Application/Admin/View/Index/index.html`,所有控制台主要面板都会加载这个模版。通过绑定变量`mainBodyPath`来指定要访问的具体模块功能模版片段的文件名；`moduleName`指定js交互脚本加载的对应模块，采用类似Angular的组织方式，所有静态片段代码放在`Public/templete`目录下。

其中，控制台控制器，有一个积累`CommonRbacController`，该类进行了一些必要的预处理。

相关的路径说明：

* `Application/Admin/Controller` 涉及到后台控制台的相关代码
* `Application/Admin/View` 由控制器直接加载的模版，目前包括控制台的主要区域还有登录页面
* `Public/templete` 根据不同模块，由include加载进来的模版片段
* `Public/fonts` 系统用到的字体文件
* `Application/Common/Common` 一些自定义的公共函数库
* `Application/Common/Library` 一些自定义的公共类库
* `Application/Admin/Common` Admin下自定义的公共函数库


## 环境依赖
该系统基于ThinkPHP开发 `版本3.2.3`

使用ThinkPHP自带的RBAC模块，还有数据库结构，进行权限控制。该系统使用npm+Grunt+Bower工作流的方式，自动化构建和打包

```Grunt模块
{
  ……
  "devDependencies": {
    "grunt": "^0.4.5",
    "grunt-php":"^1.5.1",
    "grunt-contrib-connect":"^0.1.2",
    "grunt-contrib-uglify": "^0.8.0",
    "grunt-concat": "^0.1.6",
    "load-grunt-tasks": "^3.1.0",
    "grunt-contrib-watch": "^0.6.1",
    "grunt-contrib-sass": "^0.9.2",
    "grunt-contrib-cssmin": "^0.14.0",
    "grunt-contrib-htmlmin": "^0.4.0",
    "grunt-sass": "^0.18.0",
    "grunt-contrib-copy": "^0.8.0",
    "grunt-contrib-clean": "^0.6.0",
    "grunt-bower-task": "^0.4.0",
    "grunt-filerev": "^2.3.1",
    "grunt-usemin": "^3.1.1",
    "grunt-autoprefixer": "^3.0.3"
  }
}
```

前端开发使用Jquery+Bootstrap作为开发框架，轻量级的实现了简单的模块化，预计以后会使用requirejs来做模块化:

```前端组件
"dependencies": {
	"jquery": "1.11.3",
	"requirejs": "2.1.19",
	"bootstrap": "3.3.5"
}
```

## 目录结构（脚手架）

 * main  该目录下是完整的可运行`ThinkPHP`核心代码，所有服务器端代码， 包括视图模版。
 * src/sass  系统的样式代码，使用`sass`编写，目前未重构，是直接的css格式。
 * src/scripts  前端交互js代码，未经过压缩的原始代码。
 * resources  系统使用到的一些静态资源，包括图片或者字体。
 * build  构建中间过程中保存中间处理内容，并进行操作的目录
 * deploy  最终生成的完整部署代码，`deploy/thinkphp`下是thinkPHP框架代码，`deploy/static`下是用到的静态文件
 * database.sql  生成初始化数据的`sql`语句
 * package.json  `npm`的配置文件，根据配置文件安装`grunt`模块
 * bower.json  配置前端依赖的模块，`bower`根据配置来进行安装，`exportsOverride`会覆盖原来模块默认的导出规则
 * Gruntfile.js  `Grunt`执行脚本，配置grunt执行各种自动化构建和打包任务

---

个人网站 by ["LiuWill" 刘伟](http://www.liuwill.com)
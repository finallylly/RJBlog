<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL'      	 => '2', //URL模式  
	'SESSION_AUTO_START' => true, //是否开启session
	//'LAYOUT_ON'			 => true, //是否开启布局
	//'ACTION_SUFFIX'      => 'Action', // 操作方法后缀
	'SHOW_PAGE_TRACE'    => true, // 显示页面Trace信息
	'URL_CASE_INSENSITIVE' => true, //url不区分大小写
    'LOG_RECORD' => true, // 开启日志记录
    
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1',
	'DB_USER' => 'root',
	'DB_PWD' => 'root',
	'DB_NAME' => 'blog',
	'DB_PREFIX' => 'hd_',

	//多模块配置
	'MODULE_ALLOW_LIST'   =>  array('Home','Admin'),
	'DEFAULT_MODULE'      =>  'Home',

	'APP_GROUP_MODE' => 1,
	//'APP_GROUP_PATH' => 'Modules',
	'LOAD_EXT_CONFIG' => 'verify,water',
	'URL_ROUTER_ON' => true,

	
);
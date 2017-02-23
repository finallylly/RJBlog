<?php

	return array(
		//参考网址：http://iwww.me/258.html
		'TAGLIB_BUILD_IN'	=>	'Cx,Home\TagLib\TagLibHd',
    	'TAGLIB_PRE_LOAD'	=>	'Home\TagLib\TagLibHd',




 	//'MODULE_DENY_LIST'=> array('Common','User','Admin','Install'),
	// 'MODULE_ALLOW_LIST' => array('Home','Admin'),

	'URL_ROUTE_RULES' => array(
		//'c/:id' => 'Index/List/index'
		
		// '/^c_(\d+)$/' => 'Index/List/index?id=:1',
		// '/^s_(\d+)$/' => 'Index/Show/index?id=:1'
		
		'/^c_(\d+)$/' => 'Index/List/myblog?id=:1',
		'/^s_(\d+)$/' => 'Index/Show/myblog?id=:1'
	)




		// 'APP_AUTOLOAD_PATH' => '@.TagLib',
		// 'TAGLIB_BUILD_IN' => 'Cx,Hd',

		/*'HTML_CACHE_ON' => true,
		'HTML_CACHE_RULES' => array(
			'Show:index' => array('{:module}_{:action}_{id}', 20),
		),*/
	);
?>
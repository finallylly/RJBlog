<?php
	namespace Home\Controller;
	use Home\Controller\CommonController;
	// use Think\Controller;
	
	Class TranslateController extends CommonController{
		Public function index(){
			$params = I('post.','','urldecode,trim');

			import('Class.Translate', APP_PATH);
			$Translate = new \Translate();

			$poetry = $params['poetry2'] ? $params['poetry2'] : "我在马路边,捡到一分钱";
			$poetry2 = $Translate::toPoetry($poetry);

			$this->assign(array(
					'params' => $params,
                    'poetry' => $poetry,
                    'poetry2' => $poetry2,
                ));
			$this->display();
		}
	}
?>
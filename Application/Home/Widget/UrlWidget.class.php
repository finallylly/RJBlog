<?php
	namespace Home\Controller;
	namespace Home\Widget;
	use Think\Controller;

	Class UrlWidget extends Controller {
		Public function render (){
			$field = array('id', 'name', 'url');
			$data = M('url')->field($field)->limit(5)->select();
			//return $this->renderFile('', $data);
        	$this->assign('data',$data);
        	$this->display('Widget/Url');
		}
	}
?>
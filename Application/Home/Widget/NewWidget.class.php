<?php
	namespace Home\Controller;
	namespace Home\Widget;
	use Think\Controller;

	Class NewWidget extends Controller {
		Public function render($data){
			//print_r($data);
			$limit = $data;
			$field = array('id', 'title', 'click');
			$data = M('blog')->field($field)->where(array('del' => 0))->order('time DESC')->limit($limit)->select();
			//return $this->renderFile('', $data);
			$this->assign('data',$data);
         	$this->display('Widget/New');
		}
	}
?>
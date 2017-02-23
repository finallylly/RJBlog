<?php
	namespace Home\Controller;
	namespace Home\Widget;
	use Think\Controller;
	
	Class HotWidget extends Controller {
		Public function render ($data){
			$field = array('id', 'title', 'click');
			$data= M('blog')->field($field)->where(array('del' => 0))->order('click DESC')->limit(5)->select();
			//return $this->renderFile('', $data);
			$this->assign('data',$data);
        	$this->display('Widget/Hot');
		}
	}
?>
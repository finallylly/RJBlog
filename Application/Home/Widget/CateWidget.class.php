<?php
	namespace Home\Controller;
	namespace Home\Widget;
	use Think\Controller;

	class CateWidget extends Controller {
	    public function Cate(){
	        $menu = M('Cate')->limit(5)->select();
	        $this->assign('menu',$menu);
	        $this->display('Widget/Cate');
	    }
	}

?>